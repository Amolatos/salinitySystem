<?php

namespace App\Http\Controllers;

use App\Models\SensorCalibration;
use App\Models\MangroveSpecies;
use Illuminate\Http\Request;

class SensorCalibrationController extends Controller
{
    public function index()
    {
        $calibrations = SensorCalibration::with('mangroveSpecies')->get();
        return view('sensors.calibrations.index', compact('calibrations'));
    }

    public function create()
    {
        $species = MangroveSpecies::all();
        return view('sensors.calibrations.create', compact('species'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sensor_id' => 'required|string|unique:sensor_calibrations',
            'location' => 'required|string',
            'mangrove_species_id' => 'required|exists:mangrove_species,id',
            'salinity_calibration_factor' => 'required|numeric',
            'salinity_offset' => 'required|numeric',
            'temperature_calibration_factor' => 'required|numeric',
            'temperature_offset' => 'required|numeric',
            'ph_calibration_factor' => 'required|numeric',
            'ph_offset' => 'required|numeric',
            'additional_settings' => 'nullable|json',
        ]);

        SensorCalibration::create($validated);

        return redirect()->route('sensors.calibrations.index')
            ->with('success', 'Sensor calibration created successfully');
    }

    public function edit(SensorCalibration $calibration)
    {
        $species = MangroveSpecies::all();
        return view('sensors.calibrations.edit', compact('calibration', 'species'));
    }

    public function update(Request $request, SensorCalibration $calibration)
    {
        $validated = $request->validate([
            'location' => 'required|string',
            'mangrove_species_id' => 'required|exists:mangrove_species,id',
            'salinity_calibration_factor' => 'required|numeric',
            'salinity_offset' => 'required|numeric',
            'temperature_calibration_factor' => 'required|numeric',
            'temperature_offset' => 'required|numeric',
            'ph_calibration_factor' => 'required|numeric',
            'ph_offset' => 'required|numeric',
            'additional_settings' => 'nullable|json',
        ]);

        $calibration->update($validated);

        return redirect()->route('sensors.calibrations.index')
            ->with('success', 'Sensor calibration updated successfully');
    }

    public function calibrate(Request $request)
    {
        $validated = $request->validate([
            'sensor_id' => 'required|exists:sensor_calibrations,sensor_id',
            'raw_salinity' => 'required|numeric',
            'raw_temperature' => 'required|numeric',
            'raw_ph' => 'required|numeric',
        ]);

        $calibration = SensorCalibration::where('sensor_id', $validated['sensor_id'])->firstOrFail();

        return response()->json([
            'calibrated_values' => [
                'salinity' => $calibration->calibrateSalinity($validated['raw_salinity']),
                'temperature' => $calibration->calibrateTemperature($validated['raw_temperature']),
                'ph' => $calibration->calibratePH($validated['raw_ph']),
            ]
        ]);
    }
} 