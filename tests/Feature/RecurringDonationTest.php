<?php
namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonationSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use App\Events\DonationReceived;
use Tests\TestCase;

class RecurringDonationTest extends TestCase
{
    use RefreshDatabase;

    public function test_recurring_donation_creates_subscription_record(): void
    {
        $user     = User::factory()->create(['role' => 'donor']);
        $campaign = Campaign::factory()->create(['deadline' => now()->addDays(30)]);

        $donation = Donation::factory()->create([
            'campaign_id'     => $campaign->id,
            'user_id'         => $user->id,
            'type'            => 'recurring',
            'status'          => 'completed',
            'amount'          => 200,
            'idempotency_key' => \Illuminate\Support\Str::uuid(),
            'donated_at'      => now(),
        ]);

        $this->assertDatabaseHas('donations', [
            'id'   => $donation->id,
            'type' => 'recurring',
        ]);
    }

    public function test_recurring_donation_fires_donation_received_event(): void
    {
        Event::fake();

        $user     = User::factory()->create(['role' => 'donor']);
        $campaign = Campaign::factory()->create(['deadline' => now()->addDays(30)]);

        $donation = Donation::factory()->create([
            'campaign_id'     => $campaign->id,
            'user_id'         => $user->id,
            'type'            => 'recurring',
            'status'          => 'completed',
            'amount'          => 200,
            'idempotency_key' => \Illuminate\Support\Str::uuid(),
            'donated_at'      => now(),
        ]);

        event(new DonationReceived($donation));

        Event::assertDispatched(DonationReceived::class, function ($e) use ($donation) {
            return $e->donation->id === $donation->id;
        });
    }

    public function test_subscription_can_be_cancelled(): void
    {
        $user     = User::factory()->create(['role' => 'donor']);
        $campaign = Campaign::factory()->create(['deadline' => now()->addDays(30)]);

        $sub = DonationSubscription::create([
            'user_id'                => $user->id,
            'campaign_id'            => $campaign->id,
            'amount'                 => 100,
            'currency'               => 'EGP',
            'frequency'              => 'monthly',
            'gateway_subscription_id'=> 'sub_test_' . uniqid(),
            'status'                 => 'active',
        ]);

        $sub->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        $this->assertDatabaseHas('donation_subscriptions', [
            'id'     => $sub->id,
            'status' => 'cancelled',
        ]);
        $this->assertNotNull($sub->fresh()->cancelled_at);
    }
}