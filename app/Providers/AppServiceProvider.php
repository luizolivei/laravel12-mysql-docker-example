<?php

namespace App\Providers;

use App\Domain\Categories\Repositories\CategoryRepositoryInterface;
use App\Domain\Offers\Repositories\OfferRepositoryInterface;
use App\Infrastructure\Categories\Persistence\EloquentCategoryRepository;
use App\Infrastructure\Offers\Persistence\EloquentOfferRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OfferRepositoryInterface::class, EloquentOfferRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
