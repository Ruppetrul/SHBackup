<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Mini\Repositories\MiniRepoEloquent;
use Modules\Mini\Repositories\MiniRepoEloquentInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MiniRepoEloquentInterface::class, MiniRepoEloquent::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
