<?php

namespace App\Console\Commands;

use App\Models\Device;
use App\Models\Room;
use App\Services\DeviceService;
use Illuminate\Console\Command;

class TmossDeviceCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmoss:device:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Device for a Room';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $device_code = $this->choice('Choose Supported Device Code', DeviceService::$device_codes);

        if(!in_array($device_code, array_values(DeviceService::$device_codes))){
            $this->error('Unsupported Device Code');
            return;
        }

        $device_description = $this->ask('Choose a Device Description (e.g.: Window left)', false);

        if(!$device_description){
            $this->error('No device description was specified');
            return;
        }

        $rooms = Room::select('name')->get();
        $room_list = [];
        $i = 0;
        foreach ($rooms as $room){
            $i++;
            $room_list[$i] = $room->name;
        }

        $device_room = $this->choice('Choose in which Room the device is located', $room_list);

        if(!in_array($device_room, array_values($room_list))){
            $this->error('Unsupported Device Code');
            return;
        }

        Device::create([
            'device_code' => array_search($device_code, DeviceService::$device_codes),
            'device_description' => $device_description,
            'device_state' => '0',
            'room_id' => array_search($device_room, $room_list)
        ]);

        $this->info('Device ' . $device_code . ' created successfully.');

        return 0;
    }
}
