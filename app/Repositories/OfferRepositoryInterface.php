<?php

namespace App\Repositories;

use App\Models\Offer;
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
