<?php

namespace App\Http\Controllers;

use App\DTO\Offer\OfferData;
use App\DTO\Offer\OfferFilterData;
use App\Http\Requests\Offer\OfferIndexRequest;
use App\Http\Requests\Offer\OfferStoreRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Services\Offer\OfferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OfferController extends Controller
{
    public function __construct(
        private readonly OfferService $offerService,
    ) {
    }

    /**
     * Display the offers list page or return the offers data for API clients.
     */
    public function index(OfferIndexRequest $request): Response|JsonResponse
    {
        $filters = OfferFilterData::fromArray($request->validated());

        $offers = $this->offerService->list($filters);

        if ($request->wantsJson()) {
            return OfferResource::collection($offers);
        }

        return Inertia::render('TestPage', [
            'offers' => $offers,
            'filters' => [
                'search' => $filters->search,
            ],
        ]);
    }

    public function store(OfferStoreRequest $request): RedirectResponse|JsonResponse
    {
        $offer = $this->offerService->create(OfferData::fromArray($request->validated()));

        if ($request->wantsJson()) {
            return OfferResource::make($offer)
                ->response()
                ->setStatusCode(HttpResponse::HTTP_CREATED);
        }

        $parameters = array_filter(
            ['search' => $request->input('search')],
            static fn ($value) => $value !== null && $value !== '',
        );

        return to_route('test-page', $parameters)
            ->with('success', 'Oferta criada com sucesso.');
    }

    public function destroy(Request $request, Offer $offer): RedirectResponse|JsonResponse
    {
        $this->offerService->delete($offer);

        if ($request->wantsJson()) {
            return response()->noContent();
        }

        $parameters = array_filter(
            ['search' => $request->input('search')],
            static fn ($value) => $value !== null && $value !== '',
        );

        return to_route('test-page', $parameters)
            ->with('success', 'Oferta removida com sucesso.');
    }
}
