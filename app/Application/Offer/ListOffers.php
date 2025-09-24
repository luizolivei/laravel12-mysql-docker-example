<?php

namespace App\Application\Offer;

use App\DTO\Offer\OfferFilterData;
use App\Domain\Offer\Repositories\OfferRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListOffers
{
    public function __construct(
        private readonly OfferRepositoryInterface $offers,
    ) {
    }

    public function execute(OfferFilterData $filters): Collection
    {
        return $this->offers->list($filters);
    }
}
