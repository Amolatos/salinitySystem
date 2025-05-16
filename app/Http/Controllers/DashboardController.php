<?php

namespace App\Http\Controllers;

use App\Models\SalinityReading;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function sensorLocation()
    {
        return view('sensor-location');
    }

    public function mangroveSpecies()
    {
        return view('mangrove-species');
    }

    public function getReadings()
    {
        $readings = SalinityReading::latest()
            ->take(24)
            ->get();

        return response()->json([
            'current' => $readings->first(),
            'stats' => [
                'todayReadings' => $readings->count(),
                'averageEC' => $readings->avg('ec_value'),
                'tempRange' => [
                    'min' => $readings->min('temperature'),
                    'max' => $readings->max('temperature')
                ]
            ]
        ]);
    }

    public function getLatestReading()
    {
        $reading = SalinityReading::latest()->first();
        return response()->json($reading);
    }
} 