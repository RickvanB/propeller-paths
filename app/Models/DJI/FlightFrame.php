<?php

namespace App\Models\DJI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FlightFrame extends Model
{
    use HasFactory;

    protected $table = 'flight_frames';

    /**
     * Indicates if the model should be timestamped (created_at, updated_at).
     * Keep these unless you have a specific reason not to.
     * @var bool
     */
    // public $timestamps = false;

    protected $fillable = [
        'flight_log_id', // Foreign key to FlightLog
        'frame_timestamp', // The specific timestamp for this frame
    ];

    protected $casts = [
        'frame_timestamp' => 'datetime',
    ];

    // --- Relationships ---

    /**
     * Get the flight log that owns this frame.
     */
    public function flightLog(): BelongsTo
    {
        return $this->belongsTo(FlightLog::class);
    }

    /**
     * Get the OSD data associated with the flight frame.
     */
    public function osdData(): HasOne
    {
        return $this->hasOne(FrameOsdData::class);
    }

    /**
     * Get the Gimbal data associated with the flight frame.
     */
    public function gimbalData(): HasOne
    {
        return $this->hasOne(FrameGimbalData::class);
    }

    /**
     * Get the Camera data associated with the flight frame.
     */
    public function cameraData(): HasOne
    {
        return $this->hasOne(FrameCameraData::class);
    }

    /**
     * Get the RC data associated with the flight frame.
     */
    public function rcData(): HasOne
    {
        return $this->hasOne(FrameRcData::class);
    }

    /**
     * Get the Battery data associated with the flight frame.
     */
    public function batteryData(): HasOne
    {
        return $this->hasOne(FrameBatteryData::class);
    }

    /**
     * Get the Home data associated with the flight frame.
     */
    public function homeData(): HasOne
    {
        return $this->hasOne(FrameHomeData::class);
    }
}
