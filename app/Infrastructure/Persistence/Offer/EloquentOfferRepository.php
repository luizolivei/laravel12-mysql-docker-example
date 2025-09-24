<?php

namespace App\Infrastructure\Persistence\Offer;

use App\DTO\Offer\OfferData;
use App\DTO\Offer\OfferFilterData;
use App\Domain\Offer\Repositories\OfferRepositoryInterface;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Collection;

class EloquentOfferRepository implements OfferRepositoryInterface
{
    public function __construct(
        private readonly Offer $model,
    ) {
    }

    public function list(OfferFilterData $filters): Collection
    {
        $query = $this->model->newQuery()
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
        return $this->model->newQuery()->create($data->toArray());
    }

    public function delete(Offer $offer): void
    {
        $offer->delete();
    }
}
