<?php

namespace App\Models\DJI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrameGimbalData extends Model
{
    use HasFactory;

    protected $table = 'frame_gimbal_data';
    public $timestamps = false;

    protected $fillable = [
        'flight_frame_id',
        'gimbal_mode',
        'gimbal_pitch',
        'gimbal_roll',
        'gimbal_yaw',
        'gimbal_is_pitch_at_limit',
        'gimbal_is_roll_at_limit',
        'gimbal_is_yaw_at_limit',
        'gimbal_is_stuck',
    ];

    protected $casts = [
        'gimbal_pitch' => 'float',
        'gimbal_roll' => 'float',
        'gimbal_yaw' => 'float',
        'gimbal_is_pitch_at_limit' => 'boolean',
        'gimbal_is_roll_at_limit' => 'boolean',
        'gimbal_is_yaw_at_limit' => 'boolean',
        'gimbal_is_stuck' => 'boolean',
    ];

    public function flightFrame(): BelongsTo
    {
        return $this->belongsTo(FlightFrame::class);
    }
}
