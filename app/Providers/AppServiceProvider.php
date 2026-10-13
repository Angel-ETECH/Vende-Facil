<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.favorite-button', function ($view) {
            if (! auth()->check()) { return; }
            $request = request();
            if (! $request->attributes->has('favorite_ids')) {
                $favorites = auth()->user()->favorites()->get(['store_id', 'product_id']);
                $request->attributes->set('favorite_ids', [
                    'savedStoreIds' => $favorites->pluck('store_id')->filter()->map(fn ($id) => (int) $id)->all(),
                    'savedProductIds' => $favorites->pluck('product_id')->filter()->map(fn ($id) => (int) $id)->all(),
                ]);
            }
            $view->with($request->attributes->get('favorite_ids'));
        });
        Model::preventSilentlyDiscardingAttributes($this->app->isLocal());

        Password::defaults(function () {
            return $this->app->isProduction()
                ? Password::min(10)->mixedCase()->numbers()->symbols()->uncompromised()
                : Password::min(8);
        });
    }
}
