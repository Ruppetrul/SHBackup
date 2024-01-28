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

Route::prefix('/test/{shopId}')->where(['shopId' => '[1-9][0-9]*'])->middleware('shop')->group(function () {
    Route::prefix('/mini')->group(function () {
        Route::get('/', [MiniController::class, 'mini'])->name('mini.mini');
        $controller = 'MiniController@';
        Route::get('/carts', ['uses' => $controller . 'carts'])->name('home.carts');
        Route::get('/order', ['uses' => $controller . 'order'])->name('home.order');
        Route::get('/{itemId}/detail', ['uses' => $controller . 'details'])->name('home.details');

        Route::group(['prefix' => 'ajax'], function () use ($controller) {
            Route::get('/products', ['uses' => $controller . 'getActiveProducts'])->name('products.active');
        });
    });
});
