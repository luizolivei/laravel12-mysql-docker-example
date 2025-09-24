<?php

namespace App\Providers;

use App\Domain\Offer\Repositories\OfferRepositoryInterface;
use App\Infrastructure\Persistence\Offer\EloquentOfferRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OfferRepositoryInterface::class, EloquentOfferRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
