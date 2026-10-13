<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\WhatsappClickController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\PaymentProofAdminController;
use App\Http\Controllers\Admin\StoreAdminController;


/*
|--------------------------------------------------------------------------
| HOME PÚBLICO
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])
    ->name('seo.sitemap');

Route::get('/robots.txt', [SitemapController::class, 'robots'])
    ->name('seo.robots');

Route::get('/categoria/{category:slug}', [HomeController::class, 'category'])
    ->name('categories.show');

Route::get('/ciudad/{citySlug}', [HomeController::class, 'city'])
    ->name('cities.show');


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
    ->middleware(['auth'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| ZONA PRIVADA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'throttle:60,1'])->group(function () {

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

    Route::get(
        '/mi-plan',
        [PaymentProofController::class, 'index']
    )->name('seller.plan.index');

    Route::get(
        '/pagos',
        [PaymentProofController::class, 'index']
    )->name('seller.payments.index');

    Route::post(
        '/pagos',
        [PaymentProofController::class, 'store']
    )->name('seller.payments.store');
});

Route::middleware(['auth', 'admin', 'throttle:40,1'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/ganancias', [AdminDashboardController::class, 'index'])
            ->name('earnings');

        Route::get('/tiendas', [StoreAdminController::class, 'index'])
            ->name('stores.index');

        Route::get('/tiendas/{store}', [StoreAdminController::class, 'show'])
            ->name('stores.show');

        Route::patch('/tiendas/{store}/estado', [StoreAdminController::class, 'toggle'])
            ->name('stores.toggle');

        Route::patch('/tiendas/{store}/productos-ilimitados', [StoreAdminController::class, 'toggleUnlimitedProducts'])
            ->name('stores.unlimited-products');

        Route::patch('/tiendas/{store}/destacada', [StoreAdminController::class, 'toggleFeatured'])
            ->name('stores.featured');

        Route::patch('/tiendas/{store}/verificada', [StoreAdminController::class, 'toggleVerified'])
            ->name('stores.verified');

        Route::delete('/tiendas/{store}', [StoreAdminController::class, 'destroy'])
            ->name('stores.destroy');

        Route::get('/pagos', [PaymentProofAdminController::class, 'index'])
            ->name('payments.index');

        Route::patch('/pagos/{paymentProof}/aprobar', [PaymentProofAdminController::class, 'approve'])
            ->name('payments.approve');

        Route::patch('/pagos/{paymentProof}/rechazar', [PaymentProofAdminController::class, 'reject'])
            ->name('payments.reject');

    });

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::get('/comprobantes/{paymentProof}', [PaymentProofController::class, 'show'])->name('payment-proofs.show');
    Route::get('/notificaciones', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notificaciones/leer-todas', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::patch('/notificaciones/{id}', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::get('/favoritos', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favoritos/{type}/{id}', [\App\Http\Controllers\FavoriteController::class, 'store'])->whereNumber('id')->name('favorites.store');
    Route::delete('/favoritos/{type}/{id}', [\App\Http\Controllers\FavoriteController::class, 'destroy'])->whereNumber('id')->name('favorites.destroy');
});
Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::get('/denunciar/{store:slug}', [\App\Http\Controllers\ListingReportController::class, 'create'])->name('reports.create');
    Route::post('/denunciar/{store:slug}', [\App\Http\Controllers\ListingReportController::class, 'store'])->name('reports.store');
    Route::get('/estadisticas', [\App\Http\Controllers\SellerAnalyticsController::class, 'index'])->name('seller.analytics');
});
Route::middleware(['auth', 'admin', 'throttle:40,1'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/denuncias', [\App\Http\Controllers\ListingReportController::class, 'index'])->name('reports.index');
    Route::patch('/denuncias/{listingReport}', [\App\Http\Controllers\ListingReportController::class, 'review'])->name('reports.review');
});
