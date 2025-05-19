<?php

namespace App\Http\Controllers;

use App\Models\SensorCalibration;
use App\Models\SalinityMeasurement;
use Illuminate\Http\Request;

class SensorLocationController extends Controller
{
    public function index()
    {
        $sensors = SensorCalibration::with(['mangroveSpecies', 'latestMeasurement'])->get();
        
        return view('sensor-location', [
            'sensors' => $sensors->map(function ($sensor) {
                return [
                    'id' => $sensor->id,
                    'sensor_id' => $sensor->sensor_id,
                    'location' => $sensor->location,
                    'latitude' => $sensor->latitude,
                    'longitude' => $sensor->longitude,
                    'last_reading' => $sensor->latestMeasurement ? [
                        'salinity' => $sensor->latestMeasurement->salinity_value,
                        'temperature' => $sensor->latestMeasurement->temperature,
                        'ph' => $sensor->latestMeasurement->ph_level,
                        'measured_at' => $sensor->latestMeasurement->measured_at->diffForHumans(),
                    ] : null,
                    'status' => $this->getSensorStatus($sensor),
                    'species' => $sensor->mangroveSpecies->species_name
                ];
            })
        ]);
    }

    public function updateLocation(Request $request, SensorCalibration $sensor)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $sensor->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Sensor location updated successfully'
        ]);
    }

    private function getSensorStatus($sensor)
    {
        if (!$sensor->latestMeasurement) {
            return 'offline';
        }

        $lastReading = $sensor->latestMeasurement->measured_at;
        $now = now();
        $diffInMinutes = $lastReading->diffInMinutes($now);

        if ($diffInMinutes > 30) {
            return 'offline';
        } elseif ($diffInMinutes > 10) {
            return 'warning';
        } else {
            return 'active';
        }
    }
} 