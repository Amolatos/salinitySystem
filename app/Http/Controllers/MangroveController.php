<?php

namespace App\Http\Controllers;

use App\Models\MangroveSpecies;
use App\Models\SalinityMeasurement;
use Illuminate\Http\Request;

class MangroveController extends Controller
{
    public function index()
    {
        $species = MangroveSpecies::with(['salinityMeasurements' => function($query) {
            $query->latest('measured_at')->take(1);
        }])->get();
        
        return view('mangroves.index', compact('species'));
    }

    public function show(MangroveSpecies $species)
    {
        $measurements = $species->salinityMeasurements()
            ->latest('measured_at')
            ->paginate(10);
            
        return view('mangroves.show', compact('species', 'measurements'));
    }

    public function addMeasurement(Request $request, MangroveSpecies $species)
    {
        $validated = $request->validate([
            'salinity_value' => 'required|numeric|min:0|max:100',
            'temperature' => 'nullable|numeric|min:0|max:50',
            'ph_level' => 'nullable|numeric|min:0|max:14',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['measured_at'] = now();
        
        $measurement = $species->salinityMeasurements()->create($validated);
        
        return back()->with('success', 'Measurement recorded successfully');
    }

    public function apiSubmitMeasurement(Request $request, MangroveSpecies $species)
    {
        $validated = $request->validate([
            'salinity_value' => 'required|numeric|min:0|max:100',
            'temperature' => 'nullable|numeric|min:0|max:50',
            'ph_level' => 'nullable|numeric|min:0|max:14',
            'location' => 'nullable|string|max:255',
            'api_key' => 'required|string',
        ]);

        // TODO: Implement API key validation
        
        $measurement = $species->salinityMeasurements()->create([
            'salinity_value' => $validated['salinity_value'],
            'temperature' => $validated['temperature'] ?? null,
            'ph_level' => $validated['ph_level'] ?? null,
            'location' => $validated['location'] ?? null,
            'measured_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'measurement' => $measurement
        ]);
    }
} 