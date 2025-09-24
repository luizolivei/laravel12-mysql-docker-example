<?php

namespace App\Application\Offer;

use App\DTO\Offer\OfferData;
use App\Domain\Offer\Repositories\OfferRepositoryInterface;
use App\Models\Offer;

class CreateOffer
{
    public function __construct(
        private readonly OfferRepositoryInterface $offers,
    ) {
    }

    public function execute(OfferData $data): Offer
    {
        return $this->offers->create($data);
    }
}
