<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Offer\OfferIndexRequest;
use App\Http\Requests\Offer\OfferStoreRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OfferController extends Controller
{
    public function __construct(
        private readonly OfferService $offers,
    ) {
    }

    public function index(OfferIndexRequest $request): JsonResponse
    {
        $offers = $this->offers->list($request->validated());

        return OfferResource::collection($offers)
            ->response()
            ->setStatusCode(HttpResponse::HTTP_OK);
    }

    public function store(OfferStoreRequest $request): JsonResponse
    {
        $offer = $this->offers->create($request->validated());

        return OfferResource::make($offer)
            ->response()
            ->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    public function destroy(Offer $offer): JsonResponse
    {
        $this->offers->delete($offer);

        return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
    }
}
