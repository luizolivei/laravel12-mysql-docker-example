<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OfferController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);

    Route::get('offers', [OfferController::class, 'index']);
    Route::post('offers', [OfferController::class, 'store']);
    Route::delete('offers/{offer}', [OfferController::class, 'destroy']);
});
