<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('components.layout.app', function ($view) {
            $cartCount = auth()->check()
                ? auth()->user()->carts()->count()
                : 0;

            $view->with('cartCount', $cartCount);
        });
    }
}
