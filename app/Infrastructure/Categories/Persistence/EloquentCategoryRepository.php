<?php

namespace App\Infrastructure\Categories\Persistence;

use App\Domain\Categories\Entities\Category;
use App\Domain\Categories\Repositories\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly Category $model,
    ) {
    }

    public function list(): Collection
    {
        return $this->model->newQuery()
            ->orderBy('name')
            ->get();
    }

    public function create(array $attributes): Category
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function update(Category $category, array $attributes): Category
    {
        $category->fill($attributes);
        $category->save();

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
