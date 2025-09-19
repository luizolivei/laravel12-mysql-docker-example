<?php

use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('offers', [OfferController::class, 'index']);
    Route::post('offers', [OfferController::class, 'store']);
});