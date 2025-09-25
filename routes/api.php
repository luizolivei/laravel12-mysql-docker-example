<?php

use App\Interfaces\Http\Controllers\Api\OfferController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('offers', [OfferController::class, 'index']);
    Route::post('offers', [OfferController::class, 'store']);
    Route::delete('offers/{offer}', [OfferController::class, 'destroy']);
});
