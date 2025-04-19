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
        Schema::create('frame_home_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_frame_id')
                  ->constrained('flight_frames')
                  ->onDelete('cascade');

            // Home Fields
            $table->string('home_latitude')->nullable();
            $table->string('home_longitude')->nullable();
            $table->float('home_altitude')->nullable();
            $table->float('home_height_limit')->nullable();
            $table->boolean('home_is_home_record')->default(false);
            $table->string('home_go_home_mode')->nullable();
            $table->boolean('home_is_dynamic_home_point_enabled')->default(false);
            $table->boolean('home_is_near_distance_limit')->default(false);
            $table->boolean('home_is_near_height_limit')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frame_home_data');
    }
};
