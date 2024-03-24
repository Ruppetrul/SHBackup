<?php

namespace App\Providers;

use App\Repositories\MiniEloquent;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ShopEloquent;
use App\Repositories\MiniEloquentInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MiniEloquentInterface::class, MiniEloquent::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
