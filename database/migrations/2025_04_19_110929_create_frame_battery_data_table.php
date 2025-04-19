<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('frame_battery_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_frame_id')
                  ->constrained('flight_frames')
                  ->onDelete('cascade');

            // Battery Fields
            $table->unsignedTinyInteger('battery_charge_level')->nullable();
            $table->float('battery_voltage')->nullable();
            $table->float('battery_current')->nullable();
            $table->integer('battery_current_capacity')->nullable();
            $table->integer('battery_full_capacity')->nullable();
            $table->unsignedTinyInteger('battery_cell_num')->nullable();
            $table->json('battery_cell_voltages')->nullable(); // Store array as JSON
            $table->float('battery_cell_voltage_deviation')->nullable();
            $table->float('battery_max_cell_voltage_deviation')->nullable();
            $table->float('battery_temperature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frame_battery_data');
    }
};
