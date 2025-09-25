<?php

namespace Tests\Feature\Offer;

use App\Domain\Categories\Entities\Category;
use App\Domain\Offers\Entities\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OfferApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_offers(): void
    {
        Offer::factory()->count(3)->create();

        $response = $this->getJson('/api/offers');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'price',
                        'currency',
                        'status',
                        'category_id',
                        'category' => [
                            'id',
                            'name',
                            'description',
                            'created_at',
                            'updated_at',
                        ],
                        'start_date',
                        'end_date',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_it_filters_offers_using_search_parameter(): void
    {
        Offer::factory()->create([
            'title' => 'Promoção Especial',
        ]);

        Offer::factory()->create([
            'title' => 'Oferta secundária',
        ]);

        $response = $this->getJson('/api/offers?search=promoção');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Promoção Especial');
    }

    public function test_it_creates_a_new_offer(): void
    {
        $category = Category::factory()->create();

        $payload = [
            'category_id' => $category->id,
            'title' => 'Nova Oferta',
            'description' => 'Descrição detalhada da oferta',
            'price' => 199.9,
            'currency' => 'BRL',
            'status' => 'active',
            'start_date' => Carbon::now()->toIso8601String(),
            'end_date' => Carbon::now()->addDay()->toIso8601String(),
        ];

        $response = $this->postJson('/api/offers', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.title', 'Nova Oferta')
            ->assertJsonPath('data.currency', 'BRL');

        $this->assertDatabaseHas('offers', [
            'title' => 'Nova Oferta',
            'currency' => 'BRL',
            'category_id' => $category->id,
        ]);
    }

    public function test_it_deletes_an_offer(): void
    {
        $offer = Offer::factory()->create();

        $response = $this->deleteJson("/api/offers/{$offer->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('offers', [
            'id' => $offer->id,
        ]);
    }
}
