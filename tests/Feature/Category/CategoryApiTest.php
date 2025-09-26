<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_categories(): void
    {
        Category::factory()->count(2)->create();
        Category::factory()->inactive()->create();

        $response = $this->getJson('/api/categories');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.active', true);
    }

    public function test_it_creates_a_new_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/categories', [
            'name' => 'Eletrônicos',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Eletrônicos');

        $this->assertDatabaseHas('categories', [
            'name' => 'Eletrônicos',
            'user_id' => $user->id,
            'active' => true,
        ]);
    }

    public function test_it_updates_an_existing_category(): void
    {
        $user = User::factory()->create();

        $category = Category::factory()->for($user, 'creator')->create([
            'name' => 'Moda',
        ]);

        $response = $this->actingAs($user)->putJson("/api/categories/{$category->id}", [
            'name' => 'Vestuário',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Vestuário');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Vestuário',
        ]);
    }

    public function test_it_deactivates_a_category_and_related_offers_when_owner_requests(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->for($user, 'creator')->create();
        $offers = Offer::factory()->count(2)->for($category)->create();

        $response = $this->actingAs($user)->deleteJson("/api/categories/{$category->id}");

        $response->assertNoContent();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'active' => false,
        ]);

        foreach ($offers as $offer) {
            $this->assertDatabaseHas('offers', [
                'id' => $offer->id,
                'active' => false,
            ]);
        }
    }

    public function test_it_forbids_deactivation_by_non_owner(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::factory()->for($owner, 'creator')->create();

        $response = $this->actingAs($otherUser)->deleteJson("/api/categories/{$category->id}");

        $response
            ->assertForbidden()
            ->assertJsonPath('message', 'Você não tem permissão para excluir esta categoria.');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'active' => true,
        ]);
    }
}
