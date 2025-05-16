<?php

namespace App\Console\Commands;

use App\Models\SalinityReading;
use Illuminate\Console\Command;

class GenerateTestData extends Command
{
    protected $signature = 'salinity:generate-test-data';
    protected $description = 'Generate test data for salinity readings';

    public function handle()
    {
        $this->info('Generating test data...');

        // Generate 20 readings with realistic values
        for ($i = 0; $i < 20; $i++) {
            SalinityReading::create([
                'ec_value' => rand(100, 500) / 100, // Random value between 1.00 and 5.00
                'latitude' => 14.5995 + (rand(-1000, 1000) / 10000), // Around Philippines
                'longitude' => 120.9842 + (rand(-1000, 1000) / 10000),
                'temperature' => rand(240, 320) / 10, // Random value between 24.0 and 32.0
                'timestamp' => now()->subMinutes(20 - $i), // Last 20 minutes of data
                'user_id' => 1 // Default user
            ]);
        }

        $this->info('Test data generated successfully!');
    }
} 