<?php

namespace App\Models\DJI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlightLog extends Model
{
    use HasFactory;

    protected $table = 'flight_logs';

    protected $fillable = [
        'version',
        'total_time',
        'total_distance',
        'max_height',
        'max_horizontal_speed',
        'max_vertical_speed',
        'photo_num',
        'video_time',
        'aircraft_name',
        'aircraft_sn',
        'camera_sn',
        'rc_sn',
        'app_platform',
        'app_version',
        'log_file_path',
        'processed_at',
    ];

    protected $casts = [
        'version' => 'integer',
        'total_time' => 'float',
        'total_distance' => 'float',
        'max_height' => 'float',
        'max_horizontal_speed' => 'float',
        'max_vertical_speed' => 'float',
        'photo_num' => 'integer',
        'video_time' => 'float',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the frames associated with this flight log.
     */
    public function frames(): HasMany
    {
        return $this->hasMany(FlightFrame::class);
    }
}
