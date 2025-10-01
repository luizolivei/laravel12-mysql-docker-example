<?php

namespace App\Providers;

use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\EloquentCategoryRepository;
use App\Repositories\EloquentEnterpriseRepository;
use App\Repositories\EloquentOfferRepository;
use App\Repositories\EloquentProductRepository;
use App\Repositories\EloquentScheduledOfferRepository;
use App\Repositories\EnterpriseRepositoryInterface;
use App\Repositories\OfferRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\ScheduledOfferRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(EnterpriseRepositoryInterface::class, EloquentEnterpriseRepository::class);
        $this->app->bind(OfferRepositoryInterface::class, EloquentOfferRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
        $this->app->bind(ScheduledOfferRepositoryInterface::class, EloquentScheduledOfferRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
