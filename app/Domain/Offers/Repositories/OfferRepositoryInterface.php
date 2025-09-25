<?php

namespace App\Domain\Offers\Repositories;

use App\Domain\Offers\Entities\Offer;
use Illuminate\Database\Eloquent\Collection;

interface OfferRepositoryInterface
{
    public function list(?string $search = null): Collection;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Offer;

    public function delete(Offer $offer): void;

    public function findLatest(): ?Offer;
}
