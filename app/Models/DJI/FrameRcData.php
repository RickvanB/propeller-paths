<?php

namespace App\Models\DJI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrameRcData extends Model
{
    use HasFactory;

    protected $table = 'frame_rc_data';
    public $timestamps = false;

    protected $fillable = [
        'flight_frame_id',
        'rc_downlink_signal',
        'rc_uplink_signal',
        'rc_aileron',
        'rc_elevator',
        'rc_throttle',
        'rc_rudder',
    ];

    protected $casts = [
        'rc_downlink_signal' => 'integer',
        'rc_uplink_signal' => 'integer',
        'rc_aileron' => 'integer',
        'rc_elevator' => 'integer',
        'rc_throttle' => 'integer',
        'rc_rudder' => 'integer',
    ];

    public function flightFrame(): BelongsTo
    {
        return $this->belongsTo(FlightFrame::class);
    }
}
