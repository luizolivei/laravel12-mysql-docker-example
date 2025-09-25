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

    public function list(?string $search = null, ?int $categoryId = null): Collection
    {
        $query = $this->model->newQuery()
            ->with('category')
            ->where('active', true)
            ->whereHas('category', static function ($query) {
                $query->where('active', true);
            })
            ->orderByDesc('start_date');

        if ($categoryId !== null) {
            $query->where('category_id', $categoryId);
        }

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
            ->where('active', true)
            ->whereHas('category', static function ($query) {
                $query->where('active', true);
            })
            ->latest('created_at')
            ->first();
    }
}
