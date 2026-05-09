<?php
namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymobGateway implements PaymentGatewayInterface
{
    protected string $apiKey;
    protected string $integrationId;
    protected string $iframeId;
    protected string $baseUrl = 'https://accept.paymob.com/api';

    public function __construct()
    {
        $this->apiKey        = config('services.paymob.api_key', '');
        $this->integrationId = config('services.paymob.integration_id', '');
        $this->iframeId      = config('services.paymob.iframe_id', '');
    }

    protected function authenticate(): ?string
    {
        // If no API key configured, return null gracefully
        if (empty($this->apiKey)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/auth/tokens", [
                'api_key' => $this->apiKey,
            ]);

            return $response->json('token');
        } catch (\Exception $e) {
            Log::error('PayMob authentication failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function charge(array $data): array
    {
        // If PayMob not configured, return helpful error
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'error'   => 'PayMob is not configured yet. Please use Stripe or contact support.',
            ];
        }

        try {
            $authToken = $this->authenticate();

            if (!$authToken) {
                return ['success' => false, 'error' => 'PayMob authentication failed. Check your API key.'];
            }

            // Register order
            $orderResponse = Http::timeout(10)->post("{$this->baseUrl}/ecommerce/orders", [
                'auth_token'        => $authToken,
                'delivery_needed'   => false,
                'amount_cents'      => (int) ($data['amount'] * 100),
                'currency'          => $data['currency'] ?? 'EGP',
                'merchant_order_id' => $data['idempotency_key'],
                'items'             => [],
            ]);

            $order = $orderResponse->json();

            if (empty($order['id'])) {
                return ['success' => false, 'error' => 'PayMob order creation failed.'];
            }

            // Get payment key
            $paymentKeyResponse = Http::timeout(10)->post("{$this->baseUrl}/acceptance/payment_keys", [
                'auth_token'     => $authToken,
                'amount_cents'   => (int) ($data['amount'] * 100),
                'expiration'     => 3600,
                'order_id'       => $order['id'],
                'currency'       => $data['currency'] ?? 'EGP',
                'integration_id' => $this->integrationId,
                'billing_data'   => [
                    'first_name'      => $data['name'] ?? 'N/A',
                    'last_name'       => '.',
                    'email'           => $data['email'] ?? 'N/A',
                    'phone_number'    => $data['phone'] ?? '+20000000000',
                    'apartment'       => 'NA',
                    'floor'           => 'NA',
                    'street'          => 'NA',
                    'building'        => 'NA',
                    'shipping_method' => 'NA',
                    'postal_code'     => 'NA',
                    'city'            => 'NA',
                    'country'         => 'EG',
                    'state'           => 'NA',
                ],
            ]);

            $paymentKey = $paymentKeyResponse->json('token');

            if (!$paymentKey) {
                return ['success' => false, 'error' => 'PayMob payment key generation failed.'];
            }

            $checkoutUrl = "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKey}";

            return ['success' => true, 'checkout_url' => $checkoutUrl];

        } catch (\Exception $e) {
            Log::error('PayMob charge failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'PayMob payment failed: ' . $e->getMessage()];
        }
    }

    public function createSubscription(array $data): array
    {
        return $this->charge($data);
    }

    public function cancelSubscription(string $subscriptionId): bool
    {
        return true;
    }

    public function refund(string $transactionId, float $amount): bool
    {
        if (empty($this->apiKey)) return false;

        try {
            $authToken = $this->authenticate();
            if (!$authToken) return false;

            Http::post("{$this->baseUrl}/acceptance/void_refund/refund", [
                'auth_token'     => $authToken,
                'transaction_id' => $transactionId,
                'amount_cents'   => (int) ($amount * 100),
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('PayMob refund failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function handleWebhook(array $payload, string $signature): array
    {
        $hmac = $payload['hmac'] ?? '';
        if (empty($hmac)) {
            return ['success' => false, 'error' => 'Missing HMAC'];
        }

        $obj = $payload['obj'] ?? [];
        $concatenated = implode('', [
            $obj['amount_cents'] ?? '',
            $obj['created_at'] ?? '',
            $obj['currency'] ?? '',
            $obj['error_occured'] ?? '',
            $obj['has_parent_transaction'] ?? '',
            $obj['id'] ?? '',
            $obj['integration_id'] ?? '',
            $obj['is_3d_secure'] ?? '',
            $obj['is_auth'] ?? '',
            $obj['is_capture'] ?? '',
            $obj['is_refunded'] ?? '',
            $obj['is_standalone_payment'] ?? '',
            $obj['is_voided'] ?? '',
            $obj['order']['id'] ?? '',
            $obj['owner'] ?? '',
            $obj['pending'] ?? '',
            $obj['source_data']['pan'] ?? '',
            $obj['source_data']['sub_type'] ?? '',
            $obj['source_data']['type'] ?? '',
            $obj['success'] ?? '',
        ]);

        $calculatedHmac = hash_hmac('sha512', $concatenated, $this->apiKey);

        if ($hmac !== $calculatedHmac) {
            return ['success' => false, 'error' => 'Invalid HMAC'];
        }

        return ['success' => true, 'event' => $payload];
    }
}