<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomList;
use App\Models\RoomWorkList;
use App\Models\WorkKittyList;

class WorkkittyListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $room = RoomList::first();
        if (!$room) {
            $this->command->info('No RoomList found. Seeding aborted.');
            return;
        }

        $workItems = RoomWorkList::where('room_id', $room->id)->take(3)->get();

        if ($workItems->isEmpty()) {
            $this->command->info('No RoomWorkList records found for this room.');
            return;
        }

        $workItemIds = $workItems->pluck('id')->toArray();

        $totalValue = $workItems->sum(function ($item) {
            return $item->p1_percentage_value + $item->p2_percentage_value; // p3 excluded here
        });

        WorkKittyList::create([
            'site_id' => $room->site_id,
            'room_id' => $room->id,
            'workkitty_title' => 'Work Kitty1',
            'workkitty_details' => json_encode($workItemIds), // <-- encode array as JSON string
            'workkitty_value' => $totalValue,
            'addedby' => '1',
            'status' => 'active',
        ]);
    }
}
