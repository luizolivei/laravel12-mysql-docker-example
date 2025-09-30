<?php

namespace App\Repositories;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function list(): Collection;

    //public function create(array $attributes): Product;

   // public function delete(Product $product): void;
}
