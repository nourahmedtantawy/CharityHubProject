<?php
namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class StripeGateway implements PaymentGatewayInterface
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    public function charge(array $data): array
    {
        try {
            $session = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items'           => [[
                    'price_data' => [
                        'currency'     => strtolower($data['currency'] ?? 'egp'),
                        'unit_amount'  => (int) ($data['amount'] * 100),
                        'product_data' => ['name' => $data['campaign_title']],
                    ],
                    'quantity' => 1,
                ]],
                'mode'              => 'payment',
                'success_url'       => route('donations.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'        => route('campaigns.show', $data['campaign_slug']),
                'client_reference_id' => $data['idempotency_key'],
                'metadata'          => [
                    'donation_id'     => $data['donation_id'],
                    'campaign_id'     => $data['campaign_id'],
                    'idempotency_key' => $data['idempotency_key'],
                ],
            ]);

            return ['success' => true, 'checkout_url' => $session->url, 'session_id' => $session->id];
        } catch (\Exception $e) {
            Log::error('Stripe charge failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function createSubscription(array $data): array
    {
        try {
            // Create or retrieve customer
            $customers = $this->stripe->customers->search([
                'query' => "email:'{$data['email']}'",
            ]);

            $customer = $customers->data[0] ?? $this->stripe->customers->create([
                'email' => $data['email'],
                'name'  => $data['name'],
            ]);

            // Create price
            $price = $this->stripe->prices->create([
                'currency'       => strtolower($data['currency'] ?? 'egp'),
                'unit_amount'    => (int) ($data['amount'] * 100),
                'recurring'      => ['interval' => $data['frequency'] === 'yearly' ? 'year' : 'month'],
                'product_data'   => ['name' => 'Recurring donation: ' . $data['campaign_title']],
            ]);

            $subscription = $this->stripe->subscriptions->create([
                'customer' => $customer->id,
                'items'    => [['price' => $price->id]],
                'metadata' => ['campaign_id' => $data['campaign_id']],
                'payment_behavior' => 'default_incomplete',
                'expand'   => ['latest_invoice.payment_intent'],
            ]);

            return [
                'success'         => true,
                'subscription_id' => $subscription->id,
                'client_secret'   => $subscription->latest_invoice->payment_intent->client_secret,
            ];
        } catch (\Exception $e) {
            Log::error('Stripe subscription failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            $this->stripe->subscriptions->cancel($subscriptionId);
            return true;
        } catch (\Exception $e) {
            Log::error('Stripe cancel failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function refund(string $transactionId, float $amount): bool
    {
        try {
            $this->stripe->refunds->create([
                'payment_intent' => $transactionId,
                'amount'         => (int) ($amount * 100),
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Stripe refund failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function handleWebhook(array $payload, string $signature): array
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                json_encode($payload),
                $signature,
                config('services.stripe.webhook_secret')
            );
            return ['success' => true, 'event' => $event];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}