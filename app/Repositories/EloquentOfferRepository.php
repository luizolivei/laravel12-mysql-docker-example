<?php

namespace App\Repositories;

use App\Models\Offer;
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
        return $this->model->newQuery()->create($attributes);
    }

    public function delete(Offer $offer): void
    {
        $offer->delete();
    }
}
