<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class VolunteerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'phone'         => $this->faker->phoneNumber(),
            'skills'        => ['teaching', 'driving'],
            'status'        => 'approved',
            'total_hours'   => 0,
        ];
    }
}