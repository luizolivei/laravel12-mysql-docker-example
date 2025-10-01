<?php

namespace Tests\Feature\ScheduledOffer;

use App\Models\Category;
use App\Models\ScheduledOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ScheduledOfferWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_schedule_page(): void
    {
        $user = User::factory()->create();
        $categories = Category::factory()->count(2)->create();
        ScheduledOffer::factory()->for($categories[0])->create();

        $response = $this->actingAs($user)->get(route('scheduled-offers.index'));

        $response->assertOk();

        $response->assertInertia(fn (Assert $page) => $page
            ->component('ScheduledOffers')
            ->has('categories', 2)
            ->has('scheduledOffers', 1)
        );
    }

    public function test_authenticated_user_can_schedule_offer(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $startDate = Carbon::now()->addDay();
        $endDate = (clone $startDate)->addDay();
        $scheduledFor = Carbon::now()->addHours(2);

        $payload = [
            'category_id' => $category->id,
            'title' => 'Oferta programada',
            'description' => 'Oferta criada para agendamento',
            'price' => '150.00',
            'currency' => 'BRL',
            'status' => 'draft',
            'start_date' => $startDate->toDateTimeLocalString(),
            'end_date' => $endDate->toDateTimeLocalString(),
            'scheduled_for' => $scheduledFor->toDateTimeLocalString(),
        ];

        $response = $this->actingAs($user)->post(route('scheduled-offers.store'), $payload);

        $response
            ->assertRedirect(route('scheduled-offers.index'))
            ->assertSessionHas('success', 'Oferta agendada com sucesso.');

        $this->assertDatabaseHas('scheduled_offers', [
            'title' => 'Oferta programada',
            'category_id' => $category->id,
            'currency' => 'BRL',
        ]);
    }

    public function test_command_processes_due_scheduled_offers(): void
    {
        $category = Category::factory()->create();
        $scheduled = ScheduledOffer::factory()->for($category)->create([
            'scheduled_for' => Carbon::now()->subMinute(),
            'start_date' => Carbon::now()->subHour(),
            'end_date' => Carbon::now()->addDay(),
        ]);

        $this->artisan('offers:process-scheduled')
            ->expectsOutput('Processados 1 agendamentos.')
            ->assertExitCode(0);

        $scheduled->refresh();

        $this->assertNotNull($scheduled->processed_at);
        $this->assertNotNull($scheduled->offer_id);

        $this->assertDatabaseHas('offers', [
            'id' => $scheduled->offer_id,
            'title' => $scheduled->title,
            'category_id' => $category->id,
        ]);
    }
}
