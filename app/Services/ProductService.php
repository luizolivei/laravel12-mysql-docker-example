<?php

namespace App\Services;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $product,
    ) {
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): Collection
    {
        return $this->product->list();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product
    {
        return $this->product->create($data);
    }

}
