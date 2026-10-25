<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopFrontendController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $featuredProducts = \App\Models\Product::where('status', 'ACTIVE')->latest('id')->take(8)->get();
    $categories = \App\Models\Category::where('status', 'ACTIVE')->orderBy('index', 'asc')->take(8)->get();
    return view('Frontend.home', compact('featuredProducts', 'categories'));
})->name('home');

Route::get('/about', function () {
    return view('Frontend.about');
})->name('about');

// Dynamic Shop & Shop Details
Route::get('/shop', [ShopFrontendController::class, 'shop'])->name('shop');
Route::get('/shop-details', [ShopFrontendController::class, 'shopDetails'])->name('shop-details');
Route::get('/shop/{slug}', [ShopFrontendController::class, 'shopDetails'])->name('shop.product');

// Interactive Cart & Coupon APIs
Route::get('/cart', [ShopFrontendController::class, 'cartView'])->name('cart');
Route::get('/cart/data', [ShopFrontendController::class, 'getCart'])->name('cart.data');
Route::post('/cart/add', [ShopFrontendController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [ShopFrontendController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [ShopFrontendController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/apply-coupon', [ShopFrontendController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/remove-coupon', [ShopFrontendController::class, 'removeCoupon'])->name('cart.remove-coupon');

// Checkout & Order Placement (Success & Failed)
Route::get('/checkout', [ShopFrontendController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [ShopFrontendController::class, 'processCheckout'])->name('checkout.process');
Route::get('/order-success/{order_number}', [ShopFrontendController::class, 'orderSuccess'])->name('order.success');
Route::get('/order-success/{order_number}/invoice', [ShopFrontendController::class, 'downloadInvoice'])->name('order.success.invoice');
Route::get('/order-invoice/{order_number}', [ShopFrontendController::class, 'downloadInvoice'])->name('order.invoice');
Route::get('/order-failed/{order_number}', [ShopFrontendController::class, 'orderFailed'])->name('order.failed');

// Contact & Quote
Route::get('/contact', function () {
    return view('Frontend.contact');
})->name('contact');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    return redirect()->back()->with('success', 'Thank you! Your parts inquiry has been received. One of our auto parts specialists will contact you shortly with availability and pricing.');
})->name('contact.submit');

// Policies
Route::get('/privacy-policy', function () {
    return view('Frontend.privacy-policy');
})->name('privacy-policy');

Route::get('/terms-and-condition', function () {
    return view('Frontend.terms-and-condition');
})->name('terms-and-condition');

Route::get('/return-policy', function () {
    return view('Frontend.return-policy');
})->name('return-policy');

// Fallback route for storage files when PHP symlink() is disabled on shared hosting
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*')->name('storage.fallback');

require __DIR__ . '/auth.php';
require __DIR__ . '/backend.php';
require __DIR__ . '/frontend.php';

