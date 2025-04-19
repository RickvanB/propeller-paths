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
        Schema::create('flight_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('version')->nullable();
            $table->float('total_time')->nullable();
            $table->float('total_distance')->nullable();
            $table->float('max_height')->nullable();
            $table->float('max_horizontal_speed')->nullable();
            $table->float('max_vertical_speed')->nullable();
            $table->integer('photo_num')->nullable();
            $table->float('video_time')->nullable(); // Or integer
            $table->string('aircraft_name')->nullable();
            $table->string('aircraft_sn')->nullable();
            $table->string('camera_sn')->nullable();
            $table->string('rc_sn')->nullable();
            $table->string('app_platform')->nullable();
            $table->string('app_version')->nullable();
            $table->string('log_file_path')->nullable(); // Path to original file
            $table->timestamp('processed_at')->nullable(); // When processing finished
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_logs');
    }
};
