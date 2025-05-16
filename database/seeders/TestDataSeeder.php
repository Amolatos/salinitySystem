<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SalinityReading;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create default user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Generate test readings
        for ($i = 0; $i < 20; $i++) {
            SalinityReading::create([
                'ec_value' => rand(100, 500) / 100, // Random value between 1.00 and 5.00
                'latitude' => 14.5995 + (rand(-1000, 1000) / 10000), // Around Philippines
                'longitude' => 120.9842 + (rand(-1000, 1000) / 10000),
                'temperature' => rand(240, 320) / 10, // Random value between 24.0 and 32.0
                'timestamp' => now()->subMinutes(20 - $i), // Last 20 minutes of data
                'user_id' => $user->id
            ]);
        }
    }
} 