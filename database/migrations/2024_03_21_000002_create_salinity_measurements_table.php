<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('salinity_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mangrove_species_id')->constrained()->onDelete('cascade');
            $table->decimal('salinity_value', 8, 2); // PPT (Parts Per Thousand)
            $table->decimal('temperature', 5, 2)->nullable(); // in Celsius
            $table->decimal('ph_level', 4, 2)->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('measured_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salinity_measurements');
    }
}; 