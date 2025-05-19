<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY') ?? $request->input('api_key');
        
        // TODO: Replace this with your actual API key validation logic
        $validApiKey = config('services.arduino.api_key');
        
        if (!$apiKey || $apiKey !== $validApiKey) {
            return response()->json([
                'error' => 'Invalid API key',
                'message' => 'Please provide a valid API key'
            ], 401);
        }

        return $next($request);
    }
} 