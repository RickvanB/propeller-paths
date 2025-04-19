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
        Schema::create('flight_frames', function (Blueprint $table) {
            $table->id();
            // Foreign key linking to the main flight log
            $table->foreignId('flight_log_id')
                  ->constrained('flight_logs') // Assumes table name is 'flight_logs'
                  ->onDelete('cascade'); // Delete frames if the log is deleted

            $table->dateTime('frame_timestamp')->index(); // Timestamp for the specific frame
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_frames');
    }
};
