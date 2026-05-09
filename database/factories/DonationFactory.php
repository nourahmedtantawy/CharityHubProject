<?php
namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DonationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'campaign_id'     => Campaign::factory(),
            'donor_name'      => $this->faker->name(),
            'donor_email'     => $this->faker->email(),
            'amount'          => $this->faker->randomFloat(2, 50, 5000),
            'currency'        => 'EGP',
            'type'            => 'one_time',
            'status'          => 'completed',
            'gateway'         => 'stripe',
            'idempotency_key' => Str::uuid()->toString(),
            'is_anonymous'    => false,
            'donated_at'      => now(),
        ];
    }
}