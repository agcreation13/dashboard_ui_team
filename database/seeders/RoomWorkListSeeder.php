<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomList;
use App\Models\RoomWorkList;

class RoomWorkListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Get or create RoomList entries
        $roomList = RoomList::first() ?? RoomList::create([
            'site_id' => '8',
            'room_qty' => 2,
            'room_details' => [
                ['id' => 1, 'name' => 'Bedroom'],
                ['id' => 2, 'name' => 'Living Room']
            ],
            'addedby' => '1',
            'status' => 'active',
        ]);

         // Example: loop through each room_detail and create work for it
        foreach ($roomList->room_details as $roomDetail) {
            RoomWorkList::create([
                'site_id' => $roomList->site_id,
                'room_id' => $roomList->id,
                'room_sr_no' => $roomDetail['id'], // reference from room_details

                'work_area_name' => "Ceiling",
                'work_product_name' => "Plastic paint",
                'unit' => "SFT",
                'work_area_quantity' => 50,
                'rate' => 1500,

                'labour_percentage' => 20,
                'labour_percentage_value' => 300,
                'labour_division_value' => 75,

                'p1_percentage' => 10,
                'p2_percentage' => 5,
                'p3_percentage' => 3,

                'p1_percentage_value' => 150,
                'p2_percentage_value' => 75,
                'p3_percentage_value' => 45,

                'work_day_count' => 10,
                'addedby' => '1',
                'status' => 'active',
            ]);
        }
    }
}
