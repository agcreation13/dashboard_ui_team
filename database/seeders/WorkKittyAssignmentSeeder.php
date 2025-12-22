<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomList;
use App\Models\RoomWorkList;
use App\Models\WorkKittyList;
use App\Models\WorkKittyAssignment;
use Carbon\Carbon;
use Illuminate\Support\Str;


class WorkKittyAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            WorkKittyAssignment::create([
                'labour_id' => 'LAB' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),  // e.g. LAB042
                'work_kitty_id' => rand(1, 10), // assuming you have 10 workkitty_lists records
                
                'start_date' => Carbon::now()->subDays(rand(10, 30))->toDateString(),
                'end_date' => Carbon::now()->addDays(rand(1, 10))->toDateString(),
                
                'incentive_plan' => 'Incentive plan for assignment ' . $i,
                'actual_date' => Carbon::now()->subDays(rand(5, 15))->toDateString(),
                'incentive_value' => rand(1000, 5000),

                'verified' => (bool)rand(0, 1),
                'verified_by' => 'USER' . rand(1, 10),
                'verified_date' => Carbon::now()->subDays(rand(1, 7))->toDateString(),

                'remark' => 'This is a remark for assignment ' . $i,
                'other' => 'Other info ' . Str::random(5),

                'added_by' => 'USER' . rand(1, 5),
                'status' => ['process', 'pending', 'completed'][array_rand(['process', 'pending', 'completed'])],
            ]);
        }
    }
}
