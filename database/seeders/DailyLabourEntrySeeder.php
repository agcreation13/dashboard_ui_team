<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DailyLabourEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
  
    foreach (range(1, 10) as $i) {
    // Generate an array of 1 to 3 labour entries per row
    $labourData = [];
    foreach (range(1, rand(1, 3)) as $j) {
        $labourData[] = [
            'labour_id'     => $faker->numberBetween(1, 20),
            'shift'  => $faker->randomElement(['0.5','1', '1.5', '2', '2.5', '3']), // 0.5 for half day, 1 for full day, 1.5 for night, etc.
            // 'shift'  => $faker->randomElement(['Day', 'Night', 'Full', 'Half']),
            'remark' => $faker->sentence(5),
        ];
    }

    DB::table('daily_labour_entries')->insert([
        'site_id'         => $faker->numberBetween(1, 10),
        'plan_of_date'    => $faker->date(),
        'plan_message'    => $faker->sentence(6),
        'labour_data' => json_encode($labourData),  // serialized data
        'added_by'        => $faker->numberBetween(1, 5),
        'created_at'      => now(),
        'updated_at'      => now(),
    ]);

        }
    }
}
