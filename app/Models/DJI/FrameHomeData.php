<?php

namespace App\Models\DJI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrameHomeData extends Model
{
    use HasFactory;

    protected $table = 'frame_home_data';
    public $timestamps = false;

    protected $fillable = [
        'flight_frame_id',
        'home_latitude',
        'home_longitude',
        'home_altitude',
        'home_height_limit',
        'home_is_home_record',
        'home_go_home_mode',
        'home_is_dynamic_home_point_enabled',
        'home_is_near_distance_limit',
        'home_is_near_height_limit',
    ];

    protected $casts = [
        'home_latitude' => 'string',
        'home_longitude' => 'string',
        'home_altitude' => 'float',
        'home_height_limit' => 'float',
        'home_is_home_record' => 'boolean',
        'home_is_dynamic_home_point_enabled' => 'boolean',
        'home_is_near_distance_limit' => 'boolean',
        'home_is_near_height_limit' => 'boolean',
    ];

    public function flightFrame(): BelongsTo
    {
        return $this->belongsTo(FlightFrame::class);
    }
}
