<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
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
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/shops')->group(function () {
        Route::get('/', [ShopController::class, 'index'])->name('shops.view');

        Route::prefix('/{shopId}')->group(function () {
            Route::get('/', [ShopController::class, 'detailsView'])->name('shop.details');
            Route::delete('/delete', [ShopController::class, 'shopDelete'])->name('shop.delete');
            Route::post('/add-telegram-token', [ShopController::class, 'addTelegramToken'])->name('shop.add_telegram_token');
            Route::post('/save-yookassa-token', [PaymentController::class, 'saveYookassaToken'])->name('shop.save-yookassa-token');

            Route::resource('product', ProductController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
            Route::post('product/{product}/update-image', [ProductController::class, 'productUpdateImage'])->name('product.update.image');
            Route::delete('product/{product}/delete-media', [ProductController::class, 'productDeleteMedia'])->name('product.delete.media');

            Route::resource('categories', CategoriesController::class)->only('store')
                ->names(['store' => 'categories.store']);
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
