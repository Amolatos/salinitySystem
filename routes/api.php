<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalinityReadingController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // User management routes (admin only)
    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
        Route::post('/users/{user}/toggle-active', [UserController::class, 'toggleActive']);
    });

    // Salinity reading routes
    Route::post('/readings', [SalinityReadingController::class, 'store']);
    Route::get('/readings', [SalinityReadingController::class, 'index']);
    Route::get('/readings/latest', [SalinityReadingController::class, 'latest']);
    Route::get('/readings/by-location', [SalinityReadingController::class, 'getByLocation']);
});
