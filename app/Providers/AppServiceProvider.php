<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Mini\Repositories\MiniRepoEloquent;
use Modules\Mini\Repositories\MiniRepoEloquentInterface;
use Modules\Mini\Repositories\ProductRepoEloquent;
use Modules\Mini\Repositories\ProductRepoEloquentInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MiniRepoEloquentInterface::class, MiniRepoEloquent::class);
        $this->app->bind(ProductRepoEloquentInterface::class, ProductRepoEloquent::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
