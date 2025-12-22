<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        //     'phone_no' => '000-000-0000',
        //     'status' => 'active',
        //     'profile_image' => 'default',
        // ]);
        $this->call([
            // LabourRoleSeeder::class,
            // LabourSeeder::class,
            // MaterialSeeder::class,
            // SiteDetailSeeder::class,
            // DailyLabourEntrySeeder::class,
            // DailyMaterialEntrySeeder::class,
            // RoomListSeeder::class,
            // RoomWorkListSeeder::class,
            // WorkkittyListSeeder::class,
            // WorkKittyAssignmentSeeder::class,
        ]);
    }
}

