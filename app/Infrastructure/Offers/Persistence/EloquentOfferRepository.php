<?php

namespace App\Infrastructure\Offers\Persistence;

use App\Domain\Offers\Entities\Offer;
use App\Domain\Offers\Repositories\OfferRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class EloquentOfferRepository implements OfferRepositoryInterface
{
    public function __construct(
        private readonly Offer $model,
    ) {
    }

    public function list(?string $search = null): Collection
    {
        $query = $this->model->newQuery()
            ->with('category')
            ->orderByDesc('start_date');

        if ($search !== null) {
            $normalizedSearch = trim($search);

            if ($normalizedSearch !== '') {
                $pattern = '%' . Str::of($normalizedSearch)
                    ->replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'])
                    ->value() . '%';

                $query->where(function ($query) use ($pattern) {
                    $query->where('title', 'like', $pattern)
                        ->orWhere('description', 'like', $pattern);
                });
            }
        }

        return $query->get();
    }

    public function create(array $attributes): Offer
    {
        $offer = $this->model->newQuery()->create($attributes);

        return $offer->load('category');
    }

    public function delete(Offer $offer): void
    {
        $offer->delete();
    }

    public function findLatest(): ?Offer
    {
        return $this->model->newQuery()
            ->with('category')
            ->latest('created_at')
            ->first();
    }
}
