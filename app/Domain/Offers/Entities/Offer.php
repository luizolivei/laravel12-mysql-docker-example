<?php

namespace App\Domain\Offers\Entities;

use App\Domain\Categories\Entities\Category;
use Database\Factories\OfferFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'price',
        'currency',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'category_id' => 'int',
        'price' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Category, self>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected static function newFactory(): OfferFactory
    {
        return OfferFactory::new();
    }
}
