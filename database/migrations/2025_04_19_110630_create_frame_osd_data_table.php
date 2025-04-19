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
        Schema::create('frame_osd_data', function (Blueprint $table) {
            $table->id();
            // Foreign key linking to the specific frame
            $table->foreignId('flight_frame_id')
                  ->constrained('flight_frames') // Assumes table name is 'flight_frames'
                  ->onDelete('cascade'); // Delete OSD data if the frame is deleted

            // OSD Fields (nullable where appropriate)
            $table->float('fly_time')->nullable();
            $table->decimal('latitude', 15, 10)->nullable(); // High precision for coordinates
            $table->decimal('longitude', 15, 10)->nullable();// High precision for coordinates
            $table->float('height')->nullable();
            $table->float('altitude')->nullable();
            $table->float('vps_height')->nullable();
            $table->float('x_speed')->nullable();
            $table->float('y_speed')->nullable();
            $table->float('z_speed')->nullable();
            $table->float('pitch')->nullable();
            $table->float('roll')->nullable();
            $table->float('yaw')->nullable();
            $table->string('flyc_state')->nullable();
            $table->string('flight_action')->nullable();
            $table->boolean('is_gps_used')->default(false);
            $table->string('non_gps_cause')->nullable();
            $table->unsignedTinyInteger('gps_num')->nullable(); // Assuming small number
            $table->unsignedTinyInteger('gps_level')->nullable();
            $table->unsignedTinyInteger('drone_type')->nullable();
            $table->boolean('is_swave_work')->default(false);
            $table->boolean('wave_error')->default(false);
            $table->string('go_home_status')->nullable();
            $table->string('battery_type')->nullable();
            $table->boolean('is_on_ground')->default(false);
            $table->boolean('is_motor_on')->default(false);
            $table->boolean('is_motor_blocked')->default(false);
            $table->string('motor_start_failed_cause')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frame_osd_data');
    }
};
