<?php

namespace App\Models\DJI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class FrameOsdData extends Model
{
    use HasFactory;

    protected $table = 'frame_osd_data'; // Example table name
    public $timestamps = false; // Often not needed for sub-data tables

    protected $fillable = [
        'flight_frame_id', // Foreign key to FlightFrame
        'fly_time',
        'latitude',
        'longitude',
        'height',
        'altitude',
        'vps_height',
        'x_speed',
        'y_speed',
        'z_speed',
        'pitch',
        'roll',
        'yaw',
        'flyc_state',
        'flight_action',
        'is_gps_used',
        'non_gps_cause',
        'gps_num',
        'gps_level',
        'drone_type',
        'is_swave_work',
        'wave_error',
        'go_home_status',
        'battery_type',
        'is_on_ground',
        'is_motor_on',
        'is_motor_blocked',
        'motor_start_failed_cause',
    ];

    protected $casts = [
        'fly_time' => 'float',
        'latitude' => 'decimal:15',
        'longitude' => 'decimal:15',
        'height' => 'float',
        'altitude' => 'float',
        'vps_height' => 'float',
        'x_speed' => 'float',
        'y_speed' => 'float',
        'z_speed' => 'float',
        'pitch' => 'float',
        'roll' => 'float',
        'yaw' => 'float',
        'is_gps_used' => 'boolean',
        'gps_num' => 'integer',
        'gps_level' => 'integer',
        'is_swave_work' => 'boolean',
        'wave_error' => 'boolean',
        'is_on_ground' => 'boolean',
        'is_motor_on' => 'boolean',
        'is_motor_blocked' => 'boolean',
    ];

    /**
     * Get the flight frame that owns this OSD data.
     */
    public function flightFrame(): BelongsTo
    {
        return $this->belongsTo(FlightFrame::class);
    }
}
