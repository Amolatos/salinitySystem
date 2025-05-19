<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MangroveSpecies extends Model
{
    protected $fillable = [
        'species_name',
        'common_name',
        'description',
        'optimal_salinity_range',
    ];

    /**
     * Get all salinity measurements for this species
     */
    public function salinityMeasurements(): HasMany
    {
        return $this->hasMany(SalinityMeasurement::class);
    }

    /**
     * Get the average salinity for this species
     */
    public function getAverageSalinityAttribute(): ?float
    {
        return $this->salinityMeasurements()->avg('salinity_value');
    }

    /**
     * Get the latest salinity measurement
     */
    public function getLatestSalinityAttribute(): ?float
    {
        return $this->salinityMeasurements()
            ->latest('measured_at')
            ->value('salinity_value');
    }
} 