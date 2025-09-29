<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    /** @use HasFactory<\Database\Factories\EnterpriseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
