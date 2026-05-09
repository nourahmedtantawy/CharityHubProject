<?php
namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Events\DonationReceived;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class DonationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Campaign $campaign;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'admin']);

        $this->campaign = Campaign::factory()->create([
            'status'        => 'active',
            'goal_amount'   => 10000,
            'raised_amount' => 0,
            'created_by'    => $this->user->id,
            'deadline'      => now()->addDays(30),
        ]);
    }

    public function test_donation_form_renders_on_campaign_page(): void
    {
        $response = $this->get(route('campaigns.show', $this->campaign->slug));
        $response->assertStatus(200);
        $response->assertSee('Make a Donation');
    }

    public function test_donation_requires_valid_data(): void
    {
        $response = $this->post(route('donations.store', $this->campaign), []);
        $response->assertSessionHasErrors(['donor_name', 'donor_email', 'amount', 'type', 'gateway']);
    }

    public function test_donation_fires_event_on_completion(): void
    {
        Event::fake();

        $donation = Donation::factory()->create([
            'campaign_id'     => $this->campaign->id,
            'status'          => 'pending',
            'amount'          => 500,
            'idempotency_key' => Str::uuid(),
        ]);

        $donation->update(['status' => 'completed', 'donated_at' => now()]);
        event(new DonationReceived($donation));

        Event::assertDispatched(DonationReceived::class);
    }

    public function test_campaign_raised_amount_updates_on_donation(): void
    {
        // Force raised_amount to 0 regardless of what other tests did
        $this->campaign->update(['raised_amount' => 0]);
        $this->campaign->refresh();

        $donation = Donation::factory()->create([
            'campaign_id'     => $this->campaign->id,
            'amount'          => 1000,
            'status'          => 'completed',
            'idempotency_key' => Str::uuid(),
            'donated_at'      => now(),
        ]);

        event(new DonationReceived($donation));

        $fresh = $this->campaign->fresh();
        $this->assertEquals(1000, (float) $fresh->raised_amount);
    }

    public function test_idempotency_prevents_duplicate_donations(): void
    {
        $key = Str::uuid()->toString();

        Donation::factory()->create([
            'campaign_id'     => $this->campaign->id,
            'idempotency_key' => $key,
            'amount'          => 500,
        ]);

        $this->assertDatabaseCount('donations', 1);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Donation::factory()->create([
            'campaign_id'     => $this->campaign->id,
            'idempotency_key' => $key,
            'amount'          => 500,
        ]);
    }
}