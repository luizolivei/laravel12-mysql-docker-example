<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Offer;
use App\Models\ScheduledOffer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<ScheduledOffer>
 */
class ScheduledOfferFactory extends Factory
{
    protected $model = ScheduledOffer::class;

    public function definition(): array
    {
        $startDate = Carbon::now()->addDay();
        $endDate = (clone $startDate)->addDays(3);

        return [
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'currency' => 'BRL',
            'status' => 'draft',
            'active' => true,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'scheduled_for' => Carbon::now()->addHours(2),
            'processed_at' => null,
            'offer_id' => null,
        ];
    }

    public function processed(Offer $offer): self
    {
        return $this->state(fn () => [
            'processed_at' => Carbon::now(),
            'offer_id' => $offer->id,
        ]);
    }
}
