<?php

namespace App\Repositories;

use App\Models\Enterprise;
use Illuminate\Database\Eloquent\Collection;

interface EnterpriseRepositoryInterface
{
    public function list(): Collection;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Enterprise;

    public function delete(Enterprise $enterprise): void;
}
