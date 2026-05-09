<?php
namespace Tests\Feature;

use App\Jobs\GenerateDonorCertificateJob;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonorCertificate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_certificate_job_is_dispatched_on_donation(): void
    {
        Queue::fake();

        $user     = User::factory()->create(['role' => 'admin']);
        $campaign = Campaign::factory()->create(['created_by' => $user->id, 'deadline' => now()->addDays(30)]);
        $donation = Donation::factory()->create([
            'campaign_id'     => $campaign->id,
            'status'          => 'completed',
            'idempotency_key' => \Str::uuid(),
        ]);

        GenerateDonorCertificateJob::dispatch($donation);

        Queue::assertPushed(GenerateDonorCertificateJob::class);
    }

    public function test_certificate_handles_special_characters_in_name(): void
    {
        $user     = User::factory()->create(['role' => 'admin']);
        $campaign = Campaign::factory()->create(['created_by' => $user->id, 'deadline' => now()->addDays(30)]);
        $donation = Donation::factory()->create([
            'campaign_id'  => $campaign->id,
            'donor_name'   => 'José García-Martínez',
            'amount'       => 99999999.99,
            'status'       => 'completed',
            'idempotency_key' => \Str::uuid(),
            'donated_at'   => now(),
        ]);

        Storage::fake('public');
        (new GenerateDonorCertificateJob($donation))->handle();

        $this->assertDatabaseHas('donor_certificates', ['donation_id' => $donation->id]);
    }

    public function test_certificate_verification_route_works(): void
    {
        $user     = User::factory()->create(['role' => 'admin']);
        $campaign = Campaign::factory()->create(['created_by' => $user->id, 'deadline' => now()->addDays(30)]);
        $donation = Donation::factory()->create([
            'campaign_id'     => $campaign->id,
            'status'          => 'completed',
            'idempotency_key' => \Str::uuid(),
            'donated_at'      => now(),
        ]);

        $cert = DonorCertificate::create([
            'donation_id'        => $donation->id,
            'certificate_number' => 'CH-TEST0001',
            'verification_token' => 'test-token-123',
            'issued_at'          => now(),
        ]);

        $response = $this->get(route('certificates.verify', 'test-token-123'));
        $response->assertStatus(200);
        $response->assertSee('Certificate Verified');
        $response->assertSee($donation->donor_name);
    }
}