<?php

namespace Tests\Feature\Enterprise;

use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Tests\TestCase;

class EnterpriseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_enterprises(): void
    {
        Enterprise::factory()->count(3)->create();

        $response = $this->getJson('/api/enterprises');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_it_creates_a_new_enterprise(): void
    {
        $user = User::factory()->create();

        $payload = [
            'name' => 'Nova empresa',
            'description' => 'Nova descrição',
        ];

        $response = $this->actingAs($user)->postJson('/api/enterprises', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Nova empresa')
            ->assertJsonPath('data.description', 'Nova descrição')
            ->assertJsonPath('data.active', true)
            ->assertJsonPath('data.creator.id', $user->id);

        $this->assertDatabaseHas('enterprises', [
            'name' => 'Nova empresa',
            'description' => 'Nova descrição',
            'user_id' => $user->id,
            'active' => true,
        ]);
    }

    public function test_it_requires_authentication_to_create_an_enterprise(): void
    {
        $payload = [
            'name' => 'Empresa sem usuário',
            'description' => 'Descrição',
        ];

        $response = $this->postJson('/api/enterprises', $payload);

        $response->assertStatus(HttpResponse::HTTP_UNAUTHORIZED);
    }
}