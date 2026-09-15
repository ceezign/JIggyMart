<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\GoogleOAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SellerRegistrationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Web\Admin\AdminCategoryController;
use App\Http\Controllers\Web\Admin\AdminDashboardController;
use App\Http\Controllers\Web\Admin\AdminOrderController;
use App\Http\Controllers\Web\Admin\AdminProductController;
use App\Http\Controllers\Web\Admin\AdminReviewController;
use App\Http\Controllers\Web\Admin\AdminTransactionController;
use App\Http\Controllers\Web\Admin\AdminUserController;
use App\Http\Controllers\Web\AddressController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\CustomerDashboardController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\Seller\SellerProductController;
use App\Http\Controllers\Web\SellerDashboardController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\WishlistController;
use Illuminate\Support\Facades\Route;

// ----- Public -----
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category:slug}', [ProductController::class, 'byCategory'])->name('categories.show');

// ----- Guest auth -----
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    Route::get('auth/google', [GoogleOAuthController::class, 'redirect'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleOAuthController::class, 'callback']);
});

// ----- Authenticated -----
Route::middleware(['auth', 'account.active'])->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware('signed')->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Cart & checkout
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Reviews & wishlist
    Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('wishlist/{wishlist}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.move');

    // Addresses
    Route::get('addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::patch('addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    // Customer dashboard
    Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard.customer');
    Route::get('dashboard/profile', [CustomerDashboardController::class, 'profile'])->name('dashboard.profile');
    Route::patch('dashboard/profile', [CustomerDashboardController::class, 'updateProfile'])->name('dashboard.profile.update');
    Route::patch('dashboard/password', [CustomerDashboardController::class, 'updatePassword'])->name('dashboard.password.update');
    Route::get('dashboard/transactions', [CustomerDashboardController::class, 'transactions'])->name('dashboard.transactions');

    // Become a seller
    Route::get('sell', [SellerRegistrationController::class, 'create'])->name('seller.register');
    Route::post('sell', [SellerRegistrationController::class, 'store'])->name('seller.register.store');

    // ----- Seller dashboard -----
    Route::middleware('role:seller')->prefix('seller')->name('seller.')->group(function () {
        Route::get('dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
        Route::get('orders', [SellerDashboardController::class, 'orders'])->name('orders');
        Route::get('sales', [SellerDashboardController::class, 'sales'])->name('sales');
        Route::get('profile', [SellerDashboardController::class, 'profile'])->name('profile');
        Route::patch('profile', [SellerDashboardController::class, 'updateProfile'])->name('profile.update');

        Route::resource('products', SellerProductController::class)->except(['show']);
        Route::delete('products/{product}/images/{image}', [SellerProductController::class, 'deleteImage'])->name('products.images.destroy');
    });
    // Alias used elsewhere in the app for the seller landing dashboard route.
    Route::get('/dashboard/seller', [SellerDashboardController::class, 'index'])->middleware('role:seller')->name('dashboard.seller');

    // ----- Admin dashboard -----
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/suspend', [AdminUserController::class, 'suspend'])->name('users.suspend');
        Route::post('users/{user}/reinstate', [AdminUserController::class, 'reinstate'])->name('users.reinstate');

        Route::get('sellers', [AdminUserController::class, 'sellers'])->name('sellers.index');
        Route::post('sellers/{user}/approve', [AdminUserController::class, 'approveSeller'])->name('sellers.approve');
        Route::post('sellers/{user}/reject', [AdminUserController::class, 'rejectSeller'])->name('sellers.reject');

        Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
        Route::patch('products/{product}/status', [AdminProductController::class, 'updateStatus'])->name('products.status');
        Route::delete('products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::patch('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        Route::get('transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');

        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::patch('reviews/{review}/moderate', [AdminReviewController::class, 'moderate'])->name('reviews.moderate');
    });
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->middleware('role:admin')->name('dashboard.admin');
});
