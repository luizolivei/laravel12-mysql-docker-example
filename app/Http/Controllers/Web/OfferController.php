<?php

namespace App\Http\Controllers\Web;

use App\Application\Offer\CreateOffer;
use App\Application\Offer\DeleteOffer;
use App\Application\Offer\ListOffers;
use App\DTO\Offer\OfferData;
use App\DTO\Offer\OfferFilterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Offer\OfferIndexRequest;
use App\Http\Requests\Offer\OfferStoreRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OfferController extends Controller
{
    public function __construct(
        private readonly ListOffers $listOffers,
        private readonly CreateOffer $createOffer,
        private readonly DeleteOffer $deleteOffer,
    ) {
    }

    public function index(OfferIndexRequest $request): Response
    {
        $filters = OfferFilterData::fromArray($request->validated());

        $offers = $this->listOffers->execute($filters);

        return Inertia::render('TestPage', [
            'offers' => OfferResource::collection($offers)->resolve(),
            'filters' => [
                'search' => $filters->search,
            ],
        ]);
    }

    public function store(OfferStoreRequest $request): RedirectResponse
    {
        $this->createOffer->execute(OfferData::fromArray($request->validated()));

        $parameters = array_filter(
            ['search' => $request->input('search')],
            static fn ($value) => $value !== null && $value !== '',
        );

        return to_route('test-page', $parameters)
            ->with('success', 'Oferta criada com sucesso.');
    }

    public function destroy(Request $request, Offer $offer): RedirectResponse
    {
        $this->deleteOffer->execute($offer);

        $parameters = array_filter(
            ['search' => $request->input('search')],
            static fn ($value) => $value !== null && $value !== '',
        );

        return to_route('test-page', $parameters)
            ->with('success', 'Oferta removida com sucesso.');
    }
}
