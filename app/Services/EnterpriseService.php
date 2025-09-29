<?php

namespace App\Services;

use App\Models\Enterprise;
use App\Repositories\EnterpriseRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class EnterpriseService
{
    public function __construct(
        private readonly EnterpriseRepositoryInterface $enterprises,
    ) {}

    public function list(): Collection
    {
        return $this->enterprises->list();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data, ?Authenticatable $creator = null)
    {
        if (!array_key_exists('active', $data)) {
            $data['active'] = true;
        }

        if ($creator !== null) {
            $data['user_id'] = $creator->getAuthIdentifier();
        }

        return $this->enterprises->create($data);
    }

    public function delete(Enterprise $enterprise, ?Authenticatable $creator = null)
    {
        if ($user === null || $enterprise->user_id === null || $enterprise->user_id !== $user->getAuthIdentifier()) {
            throw new AuthorizationException('Você não tem permissão para excluir esta empresa.');
        }

        return $this->enterprises->delete($enterprise);
    }
}
