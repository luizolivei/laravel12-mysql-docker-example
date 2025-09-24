<?php

use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('teste', [OfferController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('test-page');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
