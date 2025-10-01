<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduledOffer\ScheduledOfferStoreRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ScheduledOfferResource;
use App\Services\CategoryService;
use App\Services\ScheduledOfferService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ScheduledOfferController extends Controller
{
    public function __construct(
        private readonly ScheduledOfferService $scheduledOffers,
        private readonly CategoryService $categories,
    ) {
    }

    public function index(): Response
    {
        $categories = $this->categories->list();
        $scheduledOffers = $this->scheduledOffers->listUpcoming();

        return Inertia::render('ScheduledOffers', [
            'categories' => CategoryResource::collection($categories)->resolve(),
            'scheduledOffers' => ScheduledOfferResource::collection($scheduledOffers)->resolve(),
        ]);
    }

    public function store(ScheduledOfferStoreRequest $request): RedirectResponse
    {
        $this->scheduledOffers->schedule($request->validated());

        return to_route('scheduled-offers.index')
            ->with('success', 'Oferta agendada com sucesso.');
    }
}
