<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly Product $model,
    ) {
    }

    public function list(): Collection
    {
        $query = $this->model->newQuery()
            ->where('active', true);

        return $query->get();
    }

    // public function create(array $attributes): Offer
    // {
    //     $offer = $this->model->newQuery()->create($attributes);

    //     return $offer->load('category');
    // }

    //public function delete(Offer $offer): void
   // {
   //     $offer->delete();
    //}

    //public function findLatest(): ?Offer
    //{
    //    return $this->model->newQuery()
    //        ->with('category')
    //        ->where('active', true)
    //        ->whereHas('category', static function ($query) {
    ///            $query->where('active', true);
    //        })
    //        ->latest('created_at')
     //       ->first();
    //}
}
