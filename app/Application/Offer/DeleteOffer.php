<?php

namespace App\Application\Offer;

use App\Domain\Offer\Repositories\OfferRepositoryInterface;
use App\Models\Offer;

class DeleteOffer
{
    public function __construct(
        private readonly OfferRepositoryInterface $offers,
    ) {
    }

    public function execute(Offer $offer): void
    {
        $this->offers->delete($offer);
    }
}
