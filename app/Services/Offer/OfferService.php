<?php

namespace App\Services\Offer;

use App\DTO\Offer\OfferData;
use App\DTO\Offer\OfferFilterData;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

class OfferService
{
    public function __construct(
        private readonly Offer $offer,
    ) {
    }

    public function list(OfferFilterData $filters): Collection
    {
        $query = $this->offer->newQuery()
            ->orderByDesc('start_date');

        if ($filters->hasSearch()) {
            $pattern = $filters->searchPattern();

            $query->where(function ($query) use ($pattern) {
                $query->where('title', 'like', $pattern)
                    ->orWhere('description', 'like', $pattern);
            });
        }

        return $query->get();
    }

    public function create(OfferData $data): Offer
    {
        return $this->offer->newQuery()->create($data->toArray());
    }

    public function delete(Offer $offer): void
    {
        $offer->delete();
    }
}
