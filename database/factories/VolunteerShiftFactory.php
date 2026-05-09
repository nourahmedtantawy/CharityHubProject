<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VolunteerShiftFactory extends Factory
{
    public function definition(): array
    {
        $start = now()->addDays($this->faker->numberBetween(1, 30));
        return [
            'title'            => $this->faker->sentence(3),
            'starts_at'        => $start,
            'ends_at'          => $start->copy()->addHours(4),
            'max_volunteers'   => $this->faker->numberBetween(5, 20),
            'registered_count' => 0,
        ];
    }
}