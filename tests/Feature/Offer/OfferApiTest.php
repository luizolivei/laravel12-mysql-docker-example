<?php

namespace Tests\Feature\Offer;

use App\Models\Category;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OfferApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_offers(): void
    {
        Offer::factory()->count(3)->create();
        Offer::factory()->inactive()->create();

        $response = $this->getJson('/api/offers');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.active', true);
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
        $user = User::factory()->create();

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

        $response = $this->actingAs($user)->postJson('/api/offers', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.title', 'Nova Oferta')
            ->assertJsonPath('data.currency', 'BRL')
            ->assertJsonPath('data.category.name', $category->name);

        $this->assertDatabaseHas('offers', [
            'title' => 'Nova Oferta',
            'currency' => 'BRL',
            'category_id' => $category->id,
            'active' => true,
        ]);
    }

    public function test_it_filters_offers_by_category(): void
    {
        $electronics = Category::factory()->create(['name' => 'Eletrônicos']);
        $books = Category::factory()->create(['name' => 'Livros']);

        Offer::factory()->for($electronics)->create(['title' => 'Promoção TV']);
        Offer::factory()->for($books)->create(['title' => 'Desconto Livro']);
        Offer::factory()->for($books)->inactive()->create(['title' => 'Livro Antigo']);

        $response = $this->getJson("/api/offers?category_id={$electronics->id}");

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.category.name', 'Eletrônicos');
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
