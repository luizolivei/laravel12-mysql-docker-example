<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Offer\OfferIndexRequest;
use App\Http\Requests\Offer\OfferStoreRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OfferController extends Controller
{
    public function __construct(
        private readonly OfferService $offers,
    ) {
    }

    public function index(OfferIndexRequest $request): Response
    {
        $validated = $request->validated();

        $offers = $this->offers->list($validated);

        return Inertia::render('TestPage', [
            'offers' => OfferResource::collection($offers)->resolve(),
            'filters' => [
                'search' => $validated['search'] ?? null,
            ],
        ]);
    }

    public function store(OfferStoreRequest $request): RedirectResponse
    {
        $this->offers->create($request->validated());

        $parameters = array_filter(
            ['search' => $request->input('search')],
            static fn ($value) => $value !== null && $value !== '',
        );

        return to_route('test-page', $parameters)
            ->with('success', 'Oferta criada com sucesso.');
    }

    public function destroy(Request $request, Offer $offer): RedirectResponse
    {
        $this->offers->delete($offer);

        $parameters = array_filter(
            ['search' => $request->input('search')],
            static fn ($value) => $value !== null && $value !== '',
        );

        return to_route('test-page', $parameters)
            ->with('success', 'Oferta removida com sucesso.');
    }
}
