<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalinityReading extends Model
{
    protected $fillable = [
        'ec_value',
        'latitude',
        'longitude',
        'temperature',
        'timestamp',
        'user_id'
    ];

    protected $casts = [
        'ec_value' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'temperature' => 'float',
        'timestamp' => 'datetime'
    ];

    // Relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 