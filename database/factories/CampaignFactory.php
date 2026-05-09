<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CampaignFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence(4);
        return [
            'title'        => $title,
            'slug'         => Str::slug($title) . '-' . $this->faker->randomNumber(4),
            'description'  => $this->faker->paragraph(),
            'goal_amount'  => $this->faker->randomFloat(2, 1000, 100000),
            'raised_amount'=> 0,
            'currency'     => 'EGP',
            'deadline'     => now()->addDays($this->faker->numberBetween(10, 90)),
            'status'       => 'active',
            'created_by'   => User::factory(),
        ];
    }
}