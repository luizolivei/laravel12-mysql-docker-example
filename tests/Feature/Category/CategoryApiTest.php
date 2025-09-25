<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_categories(): void
    {
        Category::factory()->count(2)->create();

        $response = $this->getJson('/api/categories');

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_it_creates_a_new_category(): void
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'Eletrônicos',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Eletrônicos');

        $this->assertDatabaseHas('categories', [
            'name' => 'Eletrônicos',
        ]);
    }

    public function test_it_updates_an_existing_category(): void
    {
        $category = Category::factory()->create([
            'name' => 'Moda',
        ]);

        $response = $this->putJson("/api/categories/{$category->id}", [
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

    public function test_it_deletes_a_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
