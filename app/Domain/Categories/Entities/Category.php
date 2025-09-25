<?php

namespace App\Domain\Categories\Entities;

use App\Domain\Offers\Entities\Offer;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * @return HasMany<Offer>
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }
}
