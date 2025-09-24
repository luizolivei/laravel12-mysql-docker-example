<?php

use App\Http\Controllers\Web\OfferController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('teste', [OfferController::class, 'index'])->name('test-page');
    Route::post('offers', [OfferController::class, 'store'])->name('offers.store');
    Route::delete('offers/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
