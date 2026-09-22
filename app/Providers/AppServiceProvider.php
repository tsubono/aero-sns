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
            $userPoint = auth()->check()
                ? auth()->user()->point
                : null;

            $view->with('cartCount', $cartCount);
            $view->with('userPoint', $userPoint);
        });
    }
}
