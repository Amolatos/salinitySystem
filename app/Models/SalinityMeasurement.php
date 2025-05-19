<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalinityMeasurement extends Model
{
    protected $fillable = [
        'mangrove_species_id',
        'salinity_value',
        'temperature',
        'ph_level',
        'location',
        'notes',
        'measured_at',
    ];

    protected $casts = [
        'measured_at' => 'datetime',
        'salinity_value' => 'float',
        'temperature' => 'float',
        'ph_level' => 'float',
    ];

    /**
     * Get the mangrove species this measurement belongs to
     */
    public function mangroveSpecies(): BelongsTo
    {
        return $this->belongsTo(MangroveSpecies::class);
    }
} 