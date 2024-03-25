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
    Route::get('/create-order', ['uses' => 'CartController@createOrder'])->name('home.create.order');

    Route::group(['prefix' => 'ajax'], function () use ($controller) {
        Route::get('/products', ['uses' => $controller . 'getActiveProducts'])->name('products.active');
    });

    Route::group(['prefix' => 'cart'], static function ($router) {
        $router->post('{itemId}/delete', ['uses' => 'CartController@delete', 'as' => 'cart.delete']);
    });
    Route::group(['prefix' => 'cart-add'], static function ($router) {
        $router->post('{itemId}/{count?}', ['uses' => 'CartController@addToCart', 'as' => 'cart.add']);
    });

    /* Payment services */
    Route::group(['prefix' => 'yookassa'], static function ($router) {
        $router->any('test', ['uses' => 'YookassaController@test', 'as' => 'yookassa.test']);
        $router->any('payment/{token}', ['uses' => 'YookassaController@payment', 'as' => 'yookassa.payment.page']);
        $router->any('payment-end/{token}', ['uses' => 'YookassaController@payment_end', 'as' => 'yookassa.payment.end']);
    });
});
