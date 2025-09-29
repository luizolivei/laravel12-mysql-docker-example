<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class EnterpriseTeste extends TestCase
{
    public function test_example(): void
    {
        $this->assertTrue(false);
    }

        public function test_creates_a_new_enterprise(): void
    {
        $user = User::factory()->create();

        $payload = [
            'name' => 'Nova empresa',
            'description' => 'Nova descricao',
        ];

        $response = $this->actingAs($user)->postJson('/api/enterprises', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Nova empresa')
            ->assertJsonPath('data.description', 'Nova descricao');

        $this->assertDatabaseHas('offers', [
            'name' => 'Nova empresa',
            'description' => 'Nova descricao 1',
            'user_id' => null,
        ]);
    }
}
