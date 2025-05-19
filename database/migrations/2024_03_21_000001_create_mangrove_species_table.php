<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mangrove_species', function (Blueprint $table) {
            $table->id();
            $table->string('species_name');
            $table->string('common_name')->nullable();
            $table->text('description')->nullable();
            $table->string('optimal_salinity_range')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mangrove_species');
    }
}; 