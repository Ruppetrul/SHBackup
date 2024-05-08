<?php

namespace Modules\Mini\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Mini\Console\Commands\PrepareDefaultDB;
use Modules\Mini\Console\Commands\UpdateAllShops;

class MiniServiceProvider extends ServiceProvider
{
    /**
     * Get namespace for mini controllers.
     *
     * @var string
     */
    private string $namespace = 'Modules\Mini\Http\Controllers';

    /**
     * Get route middleware.
     *
     * @var array|string[]
     */
    private array $routeMiddleware = ['web'];

    /**
     * Get migration path.
     *
     * @var string
     */
    private string $migrationPath = '/../Database/Migrations';

    /**
     * Get name.
     *
     * @var string
     */
    private string $name = 'Mini';

    /**
     * Get view path.
     *
     * @var string
     */
    private string $viewPath = '/../Resources/views/';

    /**
     * Get middleware route.
     *
     * @var array|string[]
     */
    private array $middlewareWebRoute = ['web'];

    /**
     * Get middleware route.
     *
     * @var array|string[]
     */
    private array $middlewareApiRoute = ['api'];

    /**
     * Get route path.
     *
     * @var string
     */
    private string $routeWebPath = '/../Routes/mini_web_routes.php';

    /**
     * Get route path.
     *
     * @var string
     */
    private string $routeApiPath = '/../Routes/mini_api_routes.php';

    /**
     * Register files.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewFiles();
        $this->loadMigrationFiles();
        $this->loadRouteFiles();
        $this->loadCommandFiles();
    }

    public function boot()
    {
        $this->loadCommandFiles();
    }

    /**
     * Load product migration files.
     *
     * @return void
     */
    private function loadMigrationFiles(): void
    {
        $this->loadMigrationsFrom(__DIR__ . $this->migrationPath);
    }

    /**
     * Load panel view files.
     *
     * @return void
     */
    private function loadViewFiles(): void
    {
        $this->loadViewsFrom(__DIR__ . $this->viewPath, $this->name);
    }

    /**
     * Load panel route files.
     *
     * @return void
     */
    private function loadRouteFiles(): void
    {
        Route::middleware($this->middlewareWebRoute)
            ->namespace($this->namespace)
            ->group(__DIR__ . $this->routeWebPath);

        Route::middleware($this->middlewareApiRoute)
            ->namespace($this->namespace)
            ->group(__DIR__ . $this->routeApiPath);
    }

    /**
     * Load commands.
     *
     * @return void
     */
    private function loadCommandFiles(): void
    {
        $this->commands([
            PrepareDefaultDB::class,
            UpdateAllShops::class,
        ]);
    }
}
