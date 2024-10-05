<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use Modules\Mini\Http\Controllers\MiniController;

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
            Route::get('/product-create', [ShopController::class, 'productCreateView'])->name('product.create.view');
            Route::get('/product-edit/{itemId}', [ShopController::class, 'productEditView'])->name('product.edit.view');
            Route::delete('/delete', [ShopController::class, 'shopDelete'])->name('shop.delete');
            Route::post('/add-telegram-token', [ShopController::class, 'addTelegramToken'])->name('shop.add_telegram_token');
            Route::post('/save-yookassa-token', [PaymentController::class, 'saveYookassaToken'])->name('shop.save-yookassa-token');

            Route::resource('product', ShopController::class)->only(['store', 'update', 'destroy']);
            Route::post('product/{product}/update-image', [ShopController::class, 'productUpdateImage'])->name('product.update.image');
            Route::delete('product/{product}/delete-media', [ShopController::class, 'productDeleteMedia'])->name('product.delete.media');

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
