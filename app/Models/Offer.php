<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'title',
        'description',
        'price',
        'currency',
        'status',
        'start_date',
        'end_date',
    ];

    // Se quiser tratar datas como objetos Carbon
    protected $dates = [
        'start_date',
        'end_date',
    ];
}
