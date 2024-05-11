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

Route::prefix('/mini/{shopIdOrName}/ajax')->middleware('shop')->group(function () {
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [MiniController::class, 'getActiveProducts']);
    });
    Route::get('/product/{itemId}', [MiniController::class, 'getProduct'])->name('product');
});
