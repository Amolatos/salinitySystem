<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sensor_calibrations', function (Blueprint $table) {
            $table->id();
            $table->string('sensor_id')->unique();
            $table->string('location');
            $table->foreignId('mangrove_species_id')->constrained();
            $table->float('salinity_calibration_factor')->default(1.0);
            $table->float('salinity_offset')->default(0.0);
            $table->float('temperature_calibration_factor')->default(1.0);
            $table->float('temperature_offset')->default(0.0);
            $table->float('ph_calibration_factor')->default(1.0);
            $table->float('ph_offset')->default(0.0);
            $table->json('additional_settings')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensor_calibrations');
    }
}; 