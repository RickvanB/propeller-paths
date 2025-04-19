<?php

namespace App\Models\DJI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrameCameraData extends Model
{
    use HasFactory;

    protected $table = 'frame_camera_data';
    public $timestamps = false;

    protected $fillable = [
        'flight_frame_id',
        'camera_is_photo',
        'camera_is_video',
        'camera_sd_card_is_inserted',
        'camera_sd_card_state',
    ];

    protected $casts = [
        'camera_is_photo' => 'boolean',
        'camera_is_video' => 'boolean',
        'camera_sd_card_is_inserted' => 'boolean',
    ];

    public function flightFrame(): BelongsTo
    {
        return $this->belongsTo(FlightFrame::class);
    }
}
