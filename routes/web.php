<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Main dashboard route
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/sensor-location', [DashboardController::class, 'sensorLocation'])->name('sensor.location');
Route::get('/mangrove-species', [DashboardController::class, 'mangroveSpecies'])->name('mangrove.species');

// API Routes for dashboard data
Route::get('/api/readings', [DashboardController::class, 'getReadings']);
Route::get('/api/latest-reading', [DashboardController::class, 'getLatestReading']);
