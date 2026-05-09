<?php
namespace App\Http\Controllers;

use App\Events\DonationReceived;
use App\Models\Campaign;
use App\Models\Donation;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function store(Request $request, Campaign $campaign)
    {
        $request->validate([
            'donor_name'  => 'required|string|max:255',
            'donor_email' => 'required|email',
            'donor_phone' => 'nullable|string|max:20',
            'amount'      => 'required|numeric|min:5',
            'type'        => 'required|in:one_time,recurring',
            'frequency'   => 'nullable|in:weekly,monthly,yearly',
            'is_anonymous'=> 'boolean',
            'message'     => 'nullable|string|max:500',
            'gateway'     => 'required|in:stripe,paymob',
        ]);

        // Idempotency key prevents duplicate charges
        $idempotencyKey = Str::uuid()->toString();

        $donation = Donation::create([
            'campaign_id'     => $campaign->id,
            'user_id'         => auth()->id(),
            'donor_name'      => $request->donor_name,
            'donor_email'     => $request->donor_email,
            'donor_phone'     => $request->donor_phone,
            'amount'          => $request->amount,
            'currency'        => $campaign->currency,
            'type'            => $request->type,
            'status'          => 'pending',
            'gateway'         => $request->gateway,
            'idempotency_key' => $idempotencyKey,
            'is_anonymous'    => $request->boolean('is_anonymous'),
            'message'         => $request->message,
        ]);

        $gateway = $this->paymentService->gateway($request->gateway);

$result = $gateway->charge([
    'donation_id'     => $donation->id,
    'campaign_id'     => $campaign->id,
    'campaign_title'  => $campaign->title,
    'campaign_slug'   => $campaign->slug,
    'amount'          => $request->amount,
    'currency'        => $campaign->currency,
    'name'            => $request->donor_name,
    'email'           => $request->donor_email,
    'phone'           => $request->donor_phone,
    'idempotency_key' => $idempotencyKey,
    'frequency'       => $request->frequency,
]);

if (!$result['success']) {
    // Delete the pending donation so idempotency key isn't wasted
    $donation->delete();
    return back()
        ->withInput()
        ->with('error', $result['error'] ?? 'Payment failed. Please try again.');
}

return redirect($result['checkout_url']);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('campaigns.index')->with('error', 'Invalid session.');
        }

        // Find donation by Stripe session and mark complete
        $donation = Donation::where('status', 'pending')
            ->whereNotNull('idempotency_key')
            ->latest()
            ->first();

        if ($donation && $donation->status === 'pending') {
            $donation->update([
                'status'               => 'completed',
                'gateway_transaction_id' => $sessionId,
                'donated_at'           => now(),
            ]);

            event(new DonationReceived($donation));
        }

        return view('donations.success', compact('donation'));
    }

    public function webhook(Request $request, string $gateway)
    {
        $signature = $request->header('Stripe-Signature', '');
        $payload   = $request->all();

        $result = $this->paymentService
            ->gateway($gateway)
            ->handleWebhook($payload, $signature);

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 400);
        }

        // Handle Stripe events
        if ($gateway === 'stripe') {
            $event = $result['event'];
            if ($event->type === 'checkout.session.completed') {
                $session  = $event->data->object;
                $donation = Donation::where('idempotency_key', $session->client_reference_id)->first();
                if ($donation && $donation->status === 'pending') {
                    $donation->update([
                        'status'                 => 'completed',
                        'gateway_transaction_id' => $session->payment_intent,
                        'donated_at'             => now(),
                    ]);
                    event(new DonationReceived($donation));
                }
            }
        }

        return response()->json(['received' => true]);
    }
}