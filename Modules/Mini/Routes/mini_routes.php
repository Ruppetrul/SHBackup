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
    $controller = 'MiniController@';
    Route::get('/carts', ['uses' => $controller . 'carts'])->name('home.carts');
    Route::get('/order', ['uses' => $controller . 'order'])->name('home.order');
    Route::get('/{itemId}/detail', ['uses' => $controller . 'details'])->name('home.details');

    Route::group(['prefix' => 'ajax'], function () use ($controller) {
        Route::get('/products', ['uses' => $controller . 'getActiveProducts'])->name('products.active');
    });

    Route::group(['prefix' => 'cart'], static function ($router) {
        $router->post('{itemId}/delete', ['uses' => 'CartController@delete', 'as' => 'cart.delete']);
    });
    Route::group(['prefix' => 'cart-add'], static function ($router) {
        $router->post('{itemId}/{count}', ['uses' => 'CartController@addWithCount', 'as' => 'cart.addWithCount']);
        $router->post('{itemId}/', ['uses' => 'CartController@add', 'as' => 'cart.add']);
    });
});
