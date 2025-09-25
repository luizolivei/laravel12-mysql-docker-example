<?php

namespace App\Domain\Offers\Entities;

use Database\Factories\OfferFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';

    protected $fillable = [
        'title',
        'description',
        'price',
        'currency',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'price' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function newFactory(): OfferFactory
    {
        return OfferFactory::new();
    }
}
