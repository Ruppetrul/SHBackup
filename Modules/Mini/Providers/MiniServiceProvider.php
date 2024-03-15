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
     * Get mini path.
     *
     * @var string
     */
    private string $miniPath = '/../Routes/mini_routes.php';

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
    private array $middlewareRoute = ['web', 'verify'];

    /**
     * Get route path.
     *
     * @var string
     */
    private string $routePath = '/../Routes/mini_routes.php';

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
        $this->loadMigrationsFrom(__DIR__.$this->migrationPath);
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
        Route::middleware($this->middlewareRoute)
            ->namespace($this->namespace)
            ->group(__DIR__ . $this->routePath);
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
