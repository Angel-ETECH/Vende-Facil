<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\WhatsappClickController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StoreAdminController;


/*
|--------------------------------------------------------------------------
| HOME PÚBLICO
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');


/*
|--------------------------------------------------------------------------
| TIENDA PÚBLICA
|--------------------------------------------------------------------------
*/

Route::get('/tienda/{store:slug}', [StoreController::class, 'show'])
    ->name('stores.show');

Route::get('/tienda/{store:slug}/whatsapp', [WhatsappClickController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('stores.whatsapp');

Route::get('/tienda/{store:slug}/producto/{product}/whatsapp', [WhatsappClickController::class, 'product'])
    ->middleware('throttle:30,1')
    ->name('products.whatsapp');


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| ZONA PRIVADA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'throttle:60,1'])->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');


    Route::get(
        '/mi-tienda/crear',
        [StoreController::class, 'create']
    )->name('stores.create');

    Route::post(
        '/mi-tienda',
        [StoreController::class, 'store']
    )->name('stores.store');

    Route::get(
        '/mi-tienda/editar',
        [StoreController::class, 'edit']
    )->name('stores.edit');

    Route::put(
        '/mi-tienda',
        [StoreController::class, 'update']
    )->name('stores.update');

    Route::patch(
        '/mi-tienda/estado',
        [StoreController::class, 'toggle']
    )->name('stores.toggle');


    Route::get(
        '/productos',
        [ProductController::class, 'index']
    )->name('products.index');

    Route::get(
        '/productos/crear',
        [ProductController::class, 'create']
    )->name('products.create');

    Route::post(
        '/productos',
        [ProductController::class, 'store']
    )->name('products.store');

    Route::get(
        '/productos/{product}/editar',
        [ProductController::class, 'edit']
    )->name('products.edit');

    Route::put(
        '/productos/{product}',
        [ProductController::class, 'update']
    )->name('products.update');

    Route::patch(
        '/productos/{product}/estado',
        [ProductController::class, 'toggle']
    )->name('products.toggle');

    Route::delete(
        '/productos/{product}',
        [ProductController::class, 'destroy']
    )->name('products.destroy');
});

Route::middleware(['auth', 'admin', 'throttle:40,1'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/tiendas', [StoreAdminController::class, 'index'])
            ->name('stores.index');

        Route::get('/tiendas/{store}', [StoreAdminController::class, 'show'])
            ->name('stores.show');

        Route::patch('/tiendas/{store}/estado', [StoreAdminController::class, 'toggle'])
            ->name('stores.toggle');

        Route::delete('/tiendas/{store}', [StoreAdminController::class, 'destroy'])
            ->name('stores.destroy');

    });

require __DIR__ . '/auth.php';
