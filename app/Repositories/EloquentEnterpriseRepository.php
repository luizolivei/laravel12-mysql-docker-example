<?php

namespace App\Repositories;

use App\Models\Enterprise;
use Illuminate\Database\Eloquent\Collection;

class EloquentEnterpriseRepository implements EnterpriseRepositoryInterface
{
    public function __construct(
        private readonly Enterprise $model,
    ) {
    }

    public function list(): Collection
    {
        return $this->model->newQuery()
            ->where('description', 'like', '%jose%')
            ->orderBy('name')
            ->get();
    }

    public function create(array $attributes): Enterprise
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function delete(Enterprise $enterprise): void
    {
     $this->model->delete($category);
    }
}
