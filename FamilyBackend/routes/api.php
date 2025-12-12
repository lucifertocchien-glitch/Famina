<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Middleware\ApiAuth;

Route::group(['middleware' => ['addcors']], function () {
    Route::get('/danh-muc', [CategoryController::class, 'index']);
    Route::get('/san-pham', [ProductController::class, 'index']);
    Route::get('/products', [ProductController::class, 'index']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware([ApiAuth::class])->group(function () {
        Route::get('/gio-hang', [CartController::class, 'index']);
        Route::post('/don-dat-hang/them', [CartController::class, 'add']);
        Route::delete('/gio-hang/{id}', [CartController::class, 'remove']);

        Route::post('/orders', [OrderController::class, 'store']);
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
        Route::get('/orders/my', [OrderController::class, 'myOrders']);
    });

    // handle preflight for any api endpoint
    Route::options('{any}', function () {
        return response('', 200)->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    })->where('any', '.*');
});
