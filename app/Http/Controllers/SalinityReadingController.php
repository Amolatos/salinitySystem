<?php

namespace App\Http\Controllers;

use App\Models\SalinityReading;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalinityReadingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ec' => 'required|numeric',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'temperature' => 'required|numeric',
        ]);

        $reading = SalinityReading::create([
            'ec_value' => $validated['ec'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'temperature' => $validated['temperature'],
            'timestamp' => now(),
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Reading stored successfully',
            'data' => $reading
        ], 201);
    }

    public function index(Request $request)
    {
        $period = $request->get('period', '24h');
        
        $query = SalinityReading::query();
        
        // Filter by time period
        switch ($period) {
            case '24h':
                $query->where('timestamp', '>=', now()->subHours(24));
                break;
            case '7d':
                $query->where('timestamp', '>=', now()->subDays(7));
                break;
            case '30d':
                $query->where('timestamp', '>=', now()->subDays(30));
                break;
            case 'all':
                // No filter needed
                break;
            default:
                $query->where('timestamp', '>=', now()->subHours(24));
        }

        $readings = $query->latest('timestamp')->get();

        // Calculate statistics
        $stats = [
            'total' => $readings->count(),
            'avgEC' => $readings->avg('ec_value'),
            'minTemp' => $readings->min('temperature'),
            'maxTemp' => $readings->max('temperature'),
            'latestEC' => $readings->first()?->ec_value ?? 0,
            'periodStart' => $readings->last()?->timestamp ?? now(),
            'periodEnd' => $readings->first()?->timestamp ?? now(),
        ];

        return response()->json([
            'readings' => $readings,
            'stats' => $stats,
            'period' => $period
        ]);
    }

    public function latest()
    {
        $reading = SalinityReading::latest('timestamp')->first();
        
        if (!$reading) {
            return response()->json([
                'message' => 'No readings available'
            ], 404);
        }

        return response()->json($reading);
    }

    public function getByLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:0.1|max:10' // radius in kilometers
        ]);

        // Find readings within the specified radius using the Haversine formula
        $readings = SalinityReading::selectRaw("
            *,
            (6371 * acos(
                cos(radians(?)) * 
                cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * 
                sin(radians(latitude))
            )) AS distance", [
                $validated['latitude'],
                $validated['longitude'],
                $validated['latitude']
            ])
            ->having('distance', '<=', $validated['radius'])
            ->orderBy('timestamp', 'desc')
            ->get();

        return response()->json([
            'readings' => $readings,
            'count' => $readings->count(),
            'center' => [
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude']
            ],
            'radius' => $validated['radius']
        ]);
    }
} 