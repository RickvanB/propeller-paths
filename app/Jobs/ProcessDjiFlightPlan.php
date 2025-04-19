<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\DJI\FlightLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;


class ProcessDjiFlightPlan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $diskName = 'local';

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $data
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $absoluteInputPath = Storage::disk($this->diskName)->path($this->data['path']);
        $outputFileName = pathinfo($this->data['path'], PATHINFO_FILENAME) . '.json';
        $relativeOutputPath = dirname($this->data['path']) . '/' . $outputFileName;
        $absoluteOutputPath = Storage::disk($this->diskName)->path($relativeOutputPath); // Absolute path for output option

        $workingDirectory = base_path();

        try {
            $commandString = './dji-log --api-key ' . config('dji_api_key') . ' ' . $absoluteInputPath . ' > ' . $absoluteOutputPath;
            $process = Process::fromShellCommandline($commandString, $workingDirectory);
            $process->setTimeout(300); // Increase timeout
            $process->mustRun();

            if (!Storage::disk($this->diskName)->exists($relativeOutputPath)) {
                 throw new \Exception("Command finished but output file was not found: {$relativeOutputPath}");
            }

            $this->process($absoluteOutputPath);

            Log::info("Successfully processed DJI log (using output flag): {$this->data['path']}. Output saved to: {$relativeOutputPath}");

            // Optional: Delete the original log file after successful processing
            // Storage::disk($this->diskName)->delete($this->logFilePath);

        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error("DJI log processing failed (using output flag) for: {$this->data['path']}. Error: " . $exception->getMessage());
        }
    }

    private function process(string $absoluteOutputPath): void
    {
        $output = json_decode(File::get($absoluteOutputPath), true);
        $user = User::find($this->data['user_id']);

        DB::beginTransaction();
        $flightLog = FlightLog::create([
            'version' => $output['version'],
            'total_time' => $output['details']['totalTime'],
            'total_distance' => $output['details']['totalDistance'],
            'max_height' => $output['details']['maxHeight'],
            'max_horizontal_speed' => $output['details']['maxHorizontalSpeed'],
            'max_vertical_speed' => $output['details']['maxVerticalSpeed'],
            'photo_num' => $output['details']['photoNum'],
            'video_time' => $output['details']['videoTime'],
            'aircraft_name' => $output['details']['aircraftName'],
            'aircraft_sn' => $output['details']['aircraftSn'],
            'camera_sn' => $output['details']['cameraSn'],
            'rc_sn' => $output['details']['rcSn'],
            'app_platform' => $output['details']['appPlatform'],
            'app_version' => $output['details']['appVersion'],
            'log_file_path' => $absoluteOutputPath,
            'processed_at' => now(),
            'user_id' => $user->id
        ]);

        foreach ($output['frames'] as $frameData) {
            $flightFrame = $flightLog->frames()->create([
                'frame_timestamp' => $frameData['custom']['dateTime'],
                'flight_log_id' => $flightLog->id
            ]);
            
            // OSD Data
            $osd = $frameData['osd'];
            $flightFrame->osdData()->create([
                'flight_frame_id' => $flightFrame->id,
                'fly_time' => $osd['flyTime'] ?? null,
                'latitude' => $osd['latitude'] ?? null,
                'longitude' => $osd['longitude'] ?? null,
                'height' => $osd['height'] ?? null,
                'altitude' => $osd['altitude'] ?? null,
                'vps_height' => $osd['vpsHeight'] ?? null,
                'x_speed' => $osd['xSpeed'] ?? null,
                'y_speed' => $osd['ySpeed'] ?? null,
                'z_speed' => $osd['zSpeed'] ?? null,
                'pitch' => $osd['pitch'] ?? null,
                'roll' => $osd['roll'] ?? null,
                'yaw' => $osd['yaw'] ?? null,
                'flyc_state' => $osd['flycState'] ?? null,
                'flight_action' => $osd['flightAction'] ?? null,
                'is_gps_used' => $osd['isGpdUsed'] ?? false,
                'non_gps_cause' => $osd['nonGpsCause'] ?? null,
                'gps_num' => $osd['gpsNum'] ?? null,
                'gps_level' => $osd['gpsLevel'] ?? null,
                'drone_type' => $osd['droneType'] ?? null,
                'is_swave_work' => $osd['isSwaveWork'] ?? false,
                'wave_error' => $osd['waveError'] ?? false,
                'go_home_status' => $osd['goHomeStatus'] ?? null,
                'battery_type' => $osd['batteryType'] ?? null,
                'is_on_ground' => $osd['isOnGround'] ?? false,
                'is_motor_on' => $osd['isMotorOn'] ?? false,
                'is_motor_blocked' => $osd['isMotorBlocked'] ?? false,
                'motor_start_failed_cause' => $osd['motorStartFailedCause'] ?? null,
            ]);
            
            $gimbal = $frameData['gimbal'];
            $flightFrame->gimbalData()->create([
                'flight_frame_id' => $flightFrame->id,
                'gimbal_mode' => $gimbal['mode'] ?? null,
                'gimbal_pitch' => $gimbal['pitch'] ?? null,
                'gimbal_roll' => $gimbal['roll'] ?? null,
                'gimbal_yaw' => $gimbal['yaw'] ?? null,
                'gimbal_is_pitch_at_limit' => $gimbal['isPitchAtLimit'] ?? false,
                'gimbal_is_roll_at_limit' => $gimbal['isRollAtLimit'] ?? false,
                'gimbal_is_yaw_at_limit' => $gimbal['isYawAtLimit'] ?? false,
                'gimbal_is_stuck' => $gimbal['isStuck'] ?? false,
            ]);
            
            $camera = $frameData['camera'];
            $flightFrame->cameraData()->create([
                'flight_frame_id' => $flightFrame->id,
                'camera_is_photo' => $camera['isPhoto'] ?? false,
                'camera_is_video' => $camera['isVideo'] ?? false,
                'camera_sd_card_is_inserted' => $camera['sdCardIsInserted'] ?? false,
                'camera_sd_card_state' => $camera['sdCardState'] ?? null,
            ]);

            $rc = $frameData['rc'];
            $flightFrame->rcData()->create([
                'flight_frame_id' => $flightFrame->id,
                'rc_downlink_signal' => $rc['downlinkSignal'] ?? null,
                'rc_uplink_signal' => $rc['uplinkSignal'] ?? null,
                'rc_aileron' => $rc['aileron'] ?? null,
                'rc_elevator' => $rc['elevator'] ?? null,
                'rc_throttle' => $rc['throttle'] ?? null,
                'rc_rudder' => $rc['rudder'] ?? null,
            ]);
            
            $battery = $frameData['battery'];
            $flightFrame->batteryData()->create([
                'flight_frame_id' => $flightFrame->id,
                'battery_charge_level' => $battery['chargeLevel'] ?? null,
                'battery_voltage' => $battery['voltage'] ?? null,
                'battery_current' => $battery['current'] ?? null,
                'battery_current_capacity' => $battery['currentCapacity'] ?? null,
                'battery_full_capacity' => $battery['fullCapacity'] ?? null,
                'battery_cell_num' => $battery['cellNum'] ?? null,
                'battery_cell_voltages' => $battery['cellVoltages'] ?? [],
                'battery_cell_voltage_deviation' => $battery['cellVoltageDeviation'] ?? null,
                'battery_max_cell_voltage_deviation' => $battery['maxCellVoltageDeviation'] ?? null,
                'battery_temperature' => $battery['temperature'] ?? null,
            ]);

            $home = $frameData['home'];
            $flightFrame->homeData()->create([
                'flight_frame_id' => $flightFrame->id,
                'home_latitude' => $home['latitude'] ?? null,
                'home_longitude' => $home['longitude'] ?? null,
                'home_altitude' => $home['altitude'] ?? null,
                'home_height_limit' => $home['heightLimit'] ?? null,
                'home_is_home_record' => $home['isHomeRecord'] ?? false,
                'home_go_home_mode' => $home['goHomeMode'] ?? null,
                'home_is_dynamic_home_point_enabled' => $home['isDynamicHomePointEnabled'] ?? false,
                'home_is_near_distance_limit' => $home['isNearDistanceLimit'] ?? false,
                'home_is_near_height_limit' => $home['isNearHeightLimit'] ?? false,
            ]);
        }

        DB::commit();
    }
}
