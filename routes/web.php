<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OrderItemDownloadController;
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
Route::post('/checkout/login', [CustomerAccountController::class, 'login'])->middleware('throttle:5,1')->name('customer.login');
Route::post('/checkout/register', [CustomerAccountController::class, 'register'])->name('customer.register');
Route::post('/logout', [CustomerAccountController::class, 'logout'])->middleware('auth')->name('customer.logout');
Route::get('/account/orders', [CustomerAccountController::class, 'orders'])->middleware('auth')->name('customer.orders');
Route::get('/account', [CustomerAccountController::class, 'account'])->middleware('auth')->name('customer.account');
Route::put('/account', [CustomerAccountController::class, 'updateAccount'])->middleware('auth')->name('customer.account.update');
Route::get('/checkout/confirmation/{reference}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/payment/{reference}', [CheckoutController::class, 'paymentStatusPage'])->name('checkout.payment.status');
Route::get('/checkout/payment/{reference}/check', [CheckoutController::class, 'checkPaymentStatus'])->name('checkout.payment.check');
Route::post('/checkout/payment/callback/{reference}', [CheckoutController::class, 'paymentCallback'])->name('checkout.payment.callback');
Route::get('/checkout/payment/success/{reference}', [CheckoutController::class, 'paymentSuccess'])->name('checkout.payment.success');
Route::get('/checkout/payment/cancel/{reference}', [CheckoutController::class, 'paymentCancel'])->name('checkout.payment.cancel');
Route::get('/checkout/payment/decline/{reference}', [CheckoutController::class, 'paymentDecline'])->name('checkout.payment.decline');
Route::get('/downloads/order-items/{orderItem}', OrderItemDownloadController::class)->middleware('signed')->name('order-items.download');

Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{project:slug}', [PortfolioController::class, 'show'])->name('portfolio.show');
