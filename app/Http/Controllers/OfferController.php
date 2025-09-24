<?php

namespace App\Http\Controllers;

use App\DTO\Offer\OfferFilterData;
use App\Http\Requests\Offer\OfferIndexRequest;
use App\Services\Offer\OfferService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

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
            return response()->json([
                'data' => $offers,
            ]);
        }

        return Inertia::render('TestPage', [
            'offers' => $offers,
            'filters' => [
                'search' => $filters->search,
            ],
        ]);
    }
}
