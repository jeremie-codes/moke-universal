<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\PortfolioController;

Route::get('/', [RouteController::class, 'index'])->name('home');
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product:slug}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('/cart/items/{item}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{item}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/confirmation/{reference}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/payment/{reference}', [CheckoutController::class, 'paymentStatusPage'])->name('checkout.payment.status');
Route::get('/checkout/payment/{reference}/check', [CheckoutController::class, 'checkPaymentStatus'])->name('checkout.payment.check');
Route::post('/checkout/payment/callback/{reference}', [CheckoutController::class, 'paymentCallback'])->name('checkout.payment.callback');
Route::get('/checkout/payment/success/{reference}', [CheckoutController::class, 'paymentSuccess'])->name('checkout.payment.success');
Route::get('/checkout/payment/cancel/{reference}', [CheckoutController::class, 'paymentCancel'])->name('checkout.payment.cancel');
Route::get('/checkout/payment/decline/{reference}', [CheckoutController::class, 'paymentDecline'])->name('checkout.payment.decline');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{project:slug}', [PortfolioController::class, 'show'])->name('portfolio.show');
