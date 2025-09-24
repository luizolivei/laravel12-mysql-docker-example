<?php

namespace App\Services;

use App\Models\Offer;
use App\Repositories\OfferRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class OfferService
{
    public function __construct(
        private readonly OfferRepositoryInterface $offers,
    ) {
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): Collection
    {
        $search = Arr::get($filters, 'search');

        return $this->offers->list(is_string($search) ? $search : null);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Offer
    {
        return $this->offers->create($data);
    }

    public function delete(Offer $offer): void
    {
        $this->offers->delete($offer);
    }
}
