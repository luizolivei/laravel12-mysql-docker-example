<?php

namespace Tests\Feature\Console;

use App\Domain\Offers\Entities\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GenerateDiscountedOfferCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_discounted_copy_of_the_latest_offer(): void
    {
        $latest = Offer::factory()->create([
            'price' => 100.00,
            'currency' => 'BRL',
            'status' => 'active',
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $exitCode = Artisan::call('offers:replicate-last');

        $this->assertSame(0, $exitCode);

        $this->assertSame(2, Offer::count());

        $newOffer = Offer::latest('id')->first();

        $this->assertNotNull($newOffer);
        $this->assertFalse($newOffer->is($latest));
        $this->assertSame(90.0, $newOffer->price);
        $this->assertSame($latest->title, $newOffer->title);
        $this->assertSame($latest->description, $newOffer->description);
        $this->assertSame($latest->currency, $newOffer->currency);
        $this->assertSame($latest->status, $newOffer->status);
        $this->assertSame($latest->category_id, $newOffer->category_id);
        $this->assertEquals($latest->start_date, $newOffer->start_date);
        $this->assertEquals($latest->end_date, $newOffer->end_date);
    }

    public function test_it_handles_absence_of_offers_gracefully(): void
    {
        $exitCode = Artisan::call('offers:replicate-last');

        $this->assertSame(0, $exitCode);
        $this->assertSame('No offers available to replicate.', trim(Artisan::output()));
        $this->assertSame(0, Offer::count());
    }
}
