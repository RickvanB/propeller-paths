<?php

namespace App\Models\DJI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FrameBatteryData extends Model
{
    use HasFactory;

    protected $table = 'frame_battery_data';
    public $timestamps = false;

    protected $fillable = [
        'flight_frame_id',
        'battery_charge_level',
        'battery_voltage',
        'battery_current',
        'battery_current_capacity',
        'battery_full_capacity',
        'battery_cell_num',
        'battery_cell_voltages', // Store raw JSON/array data here
        'battery_cell_voltage_deviation',
        'battery_max_cell_voltage_deviation',
        'battery_temperature',
    ];

    protected $casts = [
        'battery_charge_level' => 'integer',
        'battery_voltage' => 'float',
        'battery_current' => 'float',
        'battery_current_capacity' => 'integer',
        'battery_full_capacity' => 'integer',
        'battery_cell_num' => 'integer',
        'battery_cell_voltages' => 'array', // Cast JSON column/text to PHP array
        'battery_cell_voltage_deviation' => 'float',
        'battery_max_cell_voltage_deviation' => 'float',
        'battery_temperature' => 'float',
    ];

    public function flightFrame(): BelongsTo
    {
        return $this->belongsTo(FlightFrame::class);
    }
}
