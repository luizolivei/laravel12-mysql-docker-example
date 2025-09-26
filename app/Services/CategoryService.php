<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categories,
    ) {
    }

    public function list(): Collection
    {
        return $this->categories->list();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?Authenticatable $creator = null): Category
    {
        if (! array_key_exists('active', $data)) {
            $data['active'] = true;
        }

        if ($creator !== null) {
            $data['user_id'] = $creator->getAuthIdentifier();
        }

        return $this->categories->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Category $category, array $data): Category
    {
        return $this->categories->update($category, $data);
    }

    public function delete(Category $category, ?Authenticatable $user = null): void
    {
        if ($user === null || $category->user_id === null || $category->user_id !== $user->getAuthIdentifier()) {
            throw new AuthorizationException('Você não tem permissão para excluir esta categoria.');
        }

        if (! $category->active) {
            return;
        }

        $this->categories->delete($category);
    }
}
