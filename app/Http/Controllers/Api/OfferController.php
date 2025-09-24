<?php

namespace App\Http\Controllers\Api;

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
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OfferController extends Controller
{
    public function __construct(
        private readonly ListOffers $listOffers,
        private readonly CreateOffer $createOffer,
        private readonly DeleteOffer $deleteOffer,
    ) {
    }

    public function index(OfferIndexRequest $request): JsonResponse
    {
        $filters = OfferFilterData::fromArray($request->validated());

        $offers = $this->listOffers->execute($filters);

        return OfferResource::collection($offers)
            ->response()
            ->setStatusCode(HttpResponse::HTTP_OK);
    }

    public function store(OfferStoreRequest $request): JsonResponse
    {
        $offer = $this->createOffer->execute(OfferData::fromArray($request->validated()));

        return OfferResource::make($offer)
            ->response()
            ->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    public function destroy(Offer $offer): JsonResponse
    {
        $this->deleteOffer->execute($offer);

        return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
    }
}
