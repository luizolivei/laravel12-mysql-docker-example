<?php

namespace App\Repositories;

use App\Models\Category;
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
            ->where('active', true)
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
        if (! $category->active) {
            return;
        }

        $category->getConnection()->transaction(function () use ($category) {
            $category->forceFill(['active' => false])->save();

            $category->offers()
                ->where('active', true)
                ->update(['active' => false]);
        });
    }
}
