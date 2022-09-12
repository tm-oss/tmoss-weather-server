<?php

namespace App\Console\Commands;

use App\Models\Room;
use Illuminate\Console\Command;

class TmossRoomCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmoss:room:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Room';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $room_name = $this->ask('Enter the name of the room', false);
        if(!$room_name){
            $this->error('No room name was specified');
            return;
        }
        $room_icon = $this->ask('Choose an icon for the room (Font Awesome) (e.g.: fa-couch)', 'fa-couch');

        $next_room_order = 10;
        $last_room_order = Room::select('order')->orderBy('order', 'desc')->first();
        if($last_room_order){
            $next_room_order = $last_room_order->order+10;
        }

        $room_order = $this->ask('Choose a room order number', $next_room_order);

        Room::create([
            'name' => $room_name,
            'icon' => $room_icon,
            'order' => $room_order
        ]);

        $this->info('Room ' . $room_name . ' created successfully.');
        return 0;
    }
}
