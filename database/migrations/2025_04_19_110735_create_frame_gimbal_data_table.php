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
        Schema::create('frame_gimbal_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_frame_id')
                  ->constrained('flight_frames')
                  ->onDelete('cascade');

            // Gimbal Fields
            $table->string('gimbal_mode')->nullable();
            $table->float('gimbal_pitch')->nullable();
            $table->float('gimbal_roll')->nullable();
            $table->float('gimbal_yaw')->nullable();
            $table->boolean('gimbal_is_pitch_at_limit')->default(false);
            $table->boolean('gimbal_is_roll_at_limit')->default(false);
            $table->boolean('gimbal_is_yaw_at_limit')->default(false);
            $table->boolean('gimbal_is_stuck')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frame_gimbal_data');
    }
};
