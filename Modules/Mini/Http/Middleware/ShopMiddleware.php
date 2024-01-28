<?php

namespace Modules\Mini\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ShopMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $shopId = $request->shopId;

        $instance = DB::table('shops')->where('id', $shopId)->first();

        if ($instance) {
            Config::set('database.connections.shop', [
                'driver' => 'mysql',
                'host' => env('DB_HOST'),
                'database' => $instance->db_name,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
            ]);

            DB::setDefaultConnection('shop');
        }

        $response = $next($request);

        DB::reconnect('mysql');

        return $response;
    }
}
