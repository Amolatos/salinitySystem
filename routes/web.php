<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MangroveController;
use App\Http\Controllers\SensorCalibrationController;

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

Route::get('/mangroves', [MangroveController::class, 'index'])->name('mangroves.index');
Route::get('/mangroves/{species}', [MangroveController::class, 'show'])->name('mangroves.show');
Route::post('/mangroves/{species}/measurements', [MangroveController::class, 'addMeasurement'])->name('mangroves.measurements.store');

// Sensor calibration routes
Route::prefix('sensors')->name('sensors.')->group(function () {
    Route::get('/calibrations', [SensorCalibrationController::class, 'index'])->name('calibrations.index');
    Route::get('/calibrations/create', [SensorCalibrationController::class, 'create'])->name('calibrations.create');
    Route::post('/calibrations', [SensorCalibrationController::class, 'store'])->name('calibrations.store');
    Route::get('/calibrations/{calibration}/edit', [SensorCalibrationController::class, 'edit'])->name('calibrations.edit');
    Route::put('/calibrations/{calibration}', [SensorCalibrationController::class, 'update'])->name('calibrations.update');
});
