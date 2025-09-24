<?php

namespace App\Domain\Offer\Repositories;

use App\DTO\Offer\OfferData;
use App\DTO\Offer\OfferFilterData;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

interface OfferRepositoryInterface
{
    public function list(OfferFilterData $filters): Collection;

    public function create(OfferData $data): Offer;

    public function delete(Offer $offer): void;
}
