<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Mypage\MypageController;
use App\Http\Controllers\Mypage\OrderController as MypageOrderController;
use App\Http\Controllers\Mypage\ProfileController as MypageProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SaleController as AdminSaleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

/**
 * ユーザー機能
 */
// TOP
Route::get('/', [TopController::class, 'index'])->name('top');

// 商品一覧・詳細
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/{product}', 'show')->name('products.show');
});

// 固定ページ
Route::view('/specified-law', 'specified-law')->name('specified-law');
Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');

// お問い合わせ
Route::controller(ContactController::class)->group(function () {
    Route::get('/contact', 'index')->name('contact.index');
    Route::post('/contact', 'store')->name('contact.store');
});

/**
 * ユーザー機能 (認証必須)
 */
Route::middleware('auth')->group(function () {
    // カート
    Route::resource('cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);

    // 注文関連
    Route::controller(OrderController::class)->prefix('order')->name('order.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/buy-now', 'buyNow')->name('buy-now');
        Route::get('/complete', 'complete')->name('complete');
    });

    // マイページ
    Route::prefix('mypage')->group(function () {
        Route::get('/', [MypageController::class, 'index'])->name('mypage');

        // 会員情報設定・退会
        Route::controller(MypageProfileController::class)
            ->prefix('profile')->name('mypage.profile.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::put('/', 'update')->name('update');
                Route::put('/password', 'updatePassword')->name('password');
                Route::delete('/', 'destroy')->name('destroy');
            });

        // 注文履歴
        Route::controller(MypageOrderController::class)
            ->prefix('orders')->name('mypage.orders.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{order}', 'show')->name('show');
            });
    });

    // ポイントチャージ
    Route::controller(PointController::class)->prefix('point')->name('point.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
    });
});

/**
 * 管理者機能
 */
Route::prefix('admin')->name('admin.')->group(function () {
    // ログイン
    Route::middleware('guest:admin')
        ->controller(AdminAuthenticatedSessionController::class)->group(function () {
            Route::get('/login', 'create')->name('login');
            Route::post('/login', 'store')->name('login.store');
        });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        // 商品管理
        Route::resource('products', AdminProductController::class)->except(['show']);

        // ユーザー管理
        Route::controller(AdminUserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{user}', 'show')->name('show');
            Route::put('/{user}/status', 'status')->name('status');
        });

        // 売上管理
        Route::get('/sales', [AdminSaleController::class, 'index'])->name('sales.index');
    });
});

// ユーザー認証
require __DIR__ . '/auth.php';
