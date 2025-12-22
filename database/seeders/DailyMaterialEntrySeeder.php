<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class DailyMaterialEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.  
     */
    public function run(): void
    {
            $faker = Faker::create();

  foreach (range(1, 10) as $i) {
    // Generate an array of 1 to 3 material entries per row
    $MaterialData = [];
    foreach (range(1, rand(1, 3)) as $j) {
        $MaterialData[] = [
            'id'     => $faker->numberBetween(1, 20),
            'qty'    => $faker->numberBetween(1, 100),
            'price'  => $faker->randomFloat(2, 50, 500),
            'remark' => $faker->sentence(5),
        ];
    }

    DB::table('daily_material_entries')->insert([
        'site_id'            => $faker->numberBetween(1, 10),
        'material_add_date'  => $faker->date(),
        'material_data'      => json_encode($MaterialData),  // serialized array
        'added_by'           => $faker->numberBetween(1, 5),
        'created_at'         => now(),
        'updated_at'         => now(),
    ]);

        }
    }
}
