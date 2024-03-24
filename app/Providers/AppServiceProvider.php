<?php

namespace App\Providers;

use App\Repositories\MiniEloquent;
use Illuminate\Support\ServiceProvider;
use App\Repositories\MiniEloquentInterface;
use Modules\Mini\Repositories\MiniRepoEloquentInterface;
use Modules\Mini\Repositories\MiniRepoEloquent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MiniEloquentInterface::class, MiniEloquent::class);
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
