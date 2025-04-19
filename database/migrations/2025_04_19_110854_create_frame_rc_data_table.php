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
        Schema::create('frame_rc_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_frame_id')
                  ->constrained('flight_frames')
                  ->onDelete('cascade');

            // RC Fields
            $table->smallInteger('rc_downlink_signal')->nullable(); // +/- 100 range likely?
            $table->smallInteger('rc_uplink_signal')->nullable();
            $table->smallInteger('rc_aileron')->nullable();
            $table->smallInteger('rc_elevator')->nullable();
            $table->smallInteger('rc_throttle')->nullable();
            $table->smallInteger('rc_rudder')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frame_rc_data');
    }
};
