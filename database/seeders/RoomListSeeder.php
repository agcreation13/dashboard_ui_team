<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomList;

class RoomListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          RoomList::create([
            'site_id' => '1',
            'room_qty' => 3,
            'room_details' => [
                ['id' => 1, 'name' => 'Master Bedroom'],
                ['id' => 2, 'name' => 'Living Room'],
                ['id' => 3, 'name' => 'Kitchen']
            ],
            'addedby' => '1',  // Assuming user ID 1
            'status' => 'active',
        ]);

          RoomList::create([
            'site_id' => '3',
            'room_qty' => 4,
            'room_details' => [
                ['id' => 1, 'name' => 'Bedroom'],
                ['id' => 2, 'name' => 'Master Bedroom'],
                ['id' => 3, 'name' => 'Living Room'],
                ['id' => 4, 'name' => 'Kitchen']
            ],
            'addedby' => '1',  // Assuming user ID 1
            'status' => 'active',
        ]);
          RoomList::create([
            'site_id' => '4',
            'room_qty' => 4,
            'room_details' => [
                ['id' => 1, 'name' => 'Master Bedroom'],
                ['id' => 2, 'name' => 'Living Room'],
                ['id' => 3, 'name' => 'Kitchen'],
                ['id' => 4, 'name' => 'bathroom']
            ],
            'addedby' => '1',  // Assuming user ID 1
            'status' => 'active',
        ]);
    }
}
