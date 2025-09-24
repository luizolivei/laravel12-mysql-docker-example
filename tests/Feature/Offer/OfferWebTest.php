<?php

namespace Tests\Feature\Offer;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class OfferWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_offers_page(): void
    {
        $user = User::factory()->create();
        Offer::factory()->count(2)->create();

        $response = $this->actingAs($user)->get(route('test-page'));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) => $page
            ->component('TestPage')
            ->has('offers', 2)
            ->has('filters', fn (Assert $props) => $props
                ->where('search', null)
            )
        );
    }

    public function test_authenticated_user_can_create_offer_through_web_form(): void
    {
        $user = User::factory()->create();

        $startDate = Carbon::now()->addHour();
        $endDate = (clone $startDate)->addHour();

        $payload = [
            'title' => 'Oferta Web',
            'description' => 'Oferta criada via formulário web',
            'price' => '99.90',
            'currency' => 'BRL',
            'status' => 'draft',
            'start_date' => $startDate->toDateTimeLocalString(),
            'end_date' => $endDate->toDateTimeLocalString(),
        ];

        $response = $this->actingAs($user)->post(route('offers.store'), $payload);

        $response
            ->assertRedirect(route('test-page'))
            ->assertSessionHas('success', 'Oferta criada com sucesso.');

        $this->assertDatabaseHas('offers', [
            'title' => 'Oferta Web',
            'currency' => 'BRL',
        ]);
    }

    public function test_authenticated_user_can_delete_an_offer(): void
    {
        $user = User::factory()->create();
        $offer = Offer::factory()->create();

        $response = $this->actingAs($user)->delete(route('offers.destroy', $offer));

        $response
            ->assertRedirect(route('test-page'))
            ->assertSessionHas('success', 'Oferta removida com sucesso.');

        $this->assertDatabaseMissing('offers', [
            'id' => $offer->id,
        ]);
    }
}
