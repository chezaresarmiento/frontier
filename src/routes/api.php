<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1_1\AuthController;
use App\Http\Controllers\Api\V1_1\ProductController;
use App\Http\Controllers\Api\V1_1\WishlistController;


Route::get('/ping', fn() => ['pong' => true]);

    Route::prefix('v1.1')->group(function () {

        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);

        Route::middleware('token.auth')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('wishlist', [WishlistController::class, 'index']);
            Route::post('wishlist', [WishlistController::class, 'store']);
            Route::delete('wishlist/{productId}', [WishlistController::class, 'destroy']);
            Route::get('products', [ProductController::class, 'index']);
            Route::post('/products', [ProductController::class, 'store']);
            
        });
    });

     Route::prefix('v2.1')->group(function () { 
        /**
         * * This is a placeholder for future API versioning.
         * so we would need to migrate the controllers to a new folder structure
         * and update the namespaces accordingly.
         */

      });
