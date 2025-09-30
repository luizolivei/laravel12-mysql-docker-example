<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name", "description", "price"
    ];

    protected $dates = ['deleted_at'];

    public function cateogries() {
        return $this->belongstToMany(Category::class, 'category_product');
    }
}
