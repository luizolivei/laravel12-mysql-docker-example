<?php

namespace Tests\Feature\Category;

use App\Domain\Categories\Entities\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_it_creates_a_category(): void
    {
        $payload = [
            'name' => 'Serviços',
            'description' => 'Categoria para serviços especializados.',
        ];

        $response = $this->postJson('/api/categories', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Serviços');

        $this->assertDatabaseHas('categories', [
            'name' => 'Serviços',
        ]);
    }

    public function test_it_updates_a_category(): void
    {
        $category = Category::factory()->create([
            'name' => 'Origem',
        ]);

        $payload = [
            'name' => 'Atualizado',
            'description' => 'Descrição revisada.',
        ];

        $response = $this->putJson("/api/categories/{$category->id}", $payload);

        $response
            ->assertOk()
            ->assertJsonPath('data.name', 'Atualizado');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Atualizado',
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
