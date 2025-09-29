<?php

namespace App\Providers;

use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\EloquentCategoryRepository;
use App\Repositories\EloquentOfferRepository;
use App\Repositories\EloquentEnterpriseRepository;
use App\Repositories\OfferRepositoryInterface;
use App\Repositories\EnterpriseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EnterpriseRepositoryInterface::class, EloquentEnterpriseRepository::class);
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
