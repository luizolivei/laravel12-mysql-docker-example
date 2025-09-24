<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = $this->faker->boolean(60)
            ? $this->faker->dateTimeBetween($startDate, '+3 months')
            : null;

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => $this->faker->randomElement(['BRL', 'USD', 'EUR']),
            'status' => $this->faker->randomElement(['draft', 'active', 'expired']),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
