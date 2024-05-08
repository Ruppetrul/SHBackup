<?php

use Illuminate\Support\Facades\Route;
use Modules\Mini\Http\Controllers\MiniController;

/*
|--------------------------------------------------------------------------
| Mini routes
|--------------------------------------------------------------------------
|
| Here you can see mini routes.
|
*/

Route::prefix('/mini/{shopIdOrName}')->middleware('shop')->group(function () {
    Route::get('/', [MiniController::class, 'mini'])->name('mini.mini');
    Route::get('/detail/{itemId}', [MiniController::class, 'mini']);
    Route::get('/carts', [MiniController::class, 'mini'])->name('home.carts');

    $controller = 'MiniController@';
    Route::get('/order', ['uses' => $controller . 'order'])->name('home.order');

    Route::post('/create-order', ['uses' => 'CartController@createOrder'])->name('home.create.order');

    Route::group(['prefix' => 'cart'], static function ($router) {
        $router->post('{itemId}/delete', ['uses' => 'CartController@delete', 'as' => 'cart.delete']);
    });
    Route::group(['prefix' => 'cart-add'], static function ($router) {
        $router->post('{itemId}/{count?}', ['uses' => 'CartController@addToCart', 'as' => 'cart.add']);
    });

    /* Payment services */
    Route::group(['prefix' => 'yookassa'], static function ($router) {
        $router->any('test', ['uses' => 'YookassaController@test', 'as' => 'yookassa.test']);
    });
});
