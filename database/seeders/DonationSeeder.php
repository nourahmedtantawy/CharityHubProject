<?php
namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $donors    = User::where('role', 'donor')->get();
        $campaigns = Campaign::where('status', 'active')->get();

        $amounts = [100, 200, 250, 500, 750, 1000, 1500, 2000, 2500, 5000];

        foreach ($campaigns as $campaign) {
            $donationCount = rand(5, 15);
            for ($i = 0; $i < $donationCount; $i++) {
                $donor = $donors->random();
                $amount = $amounts[array_rand($amounts)];

                Donation::create([
                    'campaign_id'            => $campaign->id,
                    'user_id'                => $donor->id,
                    'donor_name'             => $donor->name,
                    'donor_email'            => $donor->email,
                    'amount'                 => $amount,
                    'currency'               => 'EGP',
                    'type'                   => rand(0, 4) === 0 ? 'recurring' : 'one_time',
                    'status'                 => 'completed',
                    'gateway'                => rand(0, 1) ? 'stripe' : 'paymob',
                    'gateway_transaction_id' => 'txn_' . Str::random(20),
                    'idempotency_key'        => Str::uuid()->toString(),
                    'is_anonymous'           => rand(0, 5) === 0,
                    'message'                => rand(0, 1) ? collect([
                        'May Allah bless this effort!',
                        'Keep up the great work!',
                        'Happy to support this cause.',
                        'This is a wonderful initiative.',
                        'God bless everyone involved.',
                        'Small contribution, big impact!',
                    ])->random() : null,
                    'donated_at' => now()->subDays(rand(1, 60)),
                ]);
            }
        }
    }
}