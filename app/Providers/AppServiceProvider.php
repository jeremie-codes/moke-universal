<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
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
        View::composer('layouts::header', function ($view) {
            $cart = Cart::query()
                ->where('session_id', request()->session()->getId())
                ->first();

            $view->with('cartCount', $cart?->items()->sum('quantity') ?? 0);

            $phone = Setting::first()?->phone ?? '+243 827 289 636';
            $view->with('phone', $phone);
        });
    }
}
