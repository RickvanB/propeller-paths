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
        Schema::create('frame_camera_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_frame_id')
                  ->constrained('flight_frames')
                  ->onDelete('cascade');

            // Camera Fields
            $table->boolean('camera_is_photo')->default(false);
            $table->boolean('camera_is_video')->default(false);
            $table->boolean('camera_sd_card_is_inserted')->default(false);
            $table->string('camera_sd_card_state')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frame_camera_data');
    }
};
