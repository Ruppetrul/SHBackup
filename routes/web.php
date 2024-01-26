<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::group(['prefix' => '/shops'], function () {
        Route::get('/', [ShopController::class, 'shops'])->name('shops');

        Route::group(['prefix' => '/{shopId}'], function () {
            Route::get('/', [ShopController::class, 'showDetails'])->name('shop.details');
            Route::get('/product-create', function () {
                $shopId = request()->route('shopId');
                return view('shop.product-edit', ['shopId' => $shopId]);
            })->name('product.create.view');

            Route::group(['prefix' => '/product'], function () {
                Route::post('/create', [ShopController::class, 'productCreate'])->name('product.create');
                Route::post('/delete', [ShopController::class, 'productDelete'])->name('product.delete');
            });
        });

        Route::post('/create', [ShopController::class, 'create'])->name('shops.create');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
