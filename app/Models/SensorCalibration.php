<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorCalibration extends Model
{
    protected $fillable = [
        'sensor_id',
        'location',
        'mangrove_species_id',
        'salinity_calibration_factor',
        'salinity_offset',
        'temperature_calibration_factor',
        'temperature_offset',
        'ph_calibration_factor',
        'ph_offset',
        'additional_settings',
    ];

    protected $casts = [
        'salinity_calibration_factor' => 'float',
        'salinity_offset' => 'float',
        'temperature_calibration_factor' => 'float',
        'temperature_offset' => 'float',
        'ph_calibration_factor' => 'float',
        'ph_offset' => 'float',
        'additional_settings' => 'array',
    ];

    public function mangroveSpecies(): BelongsTo
    {
        return $this->belongsTo(MangroveSpecies::class);
    }

    public function calibrateSalinity(float $rawValue): float
    {
        return ($rawValue * $this->salinity_calibration_factor) + $this->salinity_offset;
    }

    public function calibrateTemperature(float $rawValue): float
    {
        return ($rawValue * $this->temperature_calibration_factor) + $this->temperature_offset;
    }

    public function calibratePH(float $rawValue): float
    {
        return ($rawValue * $this->ph_calibration_factor) + $this->ph_offset;
    }
} 