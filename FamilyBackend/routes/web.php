<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
	return redirect()->route('staff.index');
});

// Auth routes
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->middleware('admin')->group(function () {
	// Dashboard
	Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.home');
	// Staff
	Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
	Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
	Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
	Route::get('/staff/{id}/edit', [StaffController::class, 'edit'])->name('staff.edit');
	Route::put('/staff/{id}', [StaffController::class, 'update'])->name('staff.update');
	Route::delete('/staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');

	// Products
	Route::get('/products', [ProductController::class, 'index'])->name('products.index');
	Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
	Route::post('/products', [ProductController::class, 'store'])->name('products.store');
	Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
	Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
	Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

	// Orders
	Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
	Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
	Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
	Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
	Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
	Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
	Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

	// Shipping
	Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping.index');
	Route::get('/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
	Route::post('/shipping', [ShippingController::class, 'store'])->name('shipping.store');
	Route::get('/shipping/{id}/edit', [ShippingController::class, 'edit'])->name('shipping.edit');
	Route::put('/shipping/{id}', [ShippingController::class, 'update'])->name('shipping.update');
	Route::delete('/shipping/{id}', [ShippingController::class, 'destroy'])->name('shipping.destroy');
});
