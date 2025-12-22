<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            // Alternatively, you can manually insert data
        $faker = Faker::create();
          for ($i = 0; $i < 10; $i++) {
               $status = $faker->randomElement(['active', 'deactivate']);
            DB::table('materials')->insert([
                'name' => $faker->name,
                'price_byunit' => $faker->numberBetween(400, 800),
                'unit_type' => $faker->randomElement(['kg', 'litre', 'meter', 'piece', 'set', 'roll', 'box', 'bag']),
                'status' => $status,
                'remark'    => $status === 'deactivate' ? 'Material not avl xyz region' : null,
                'addedBy' => '1', 
            ]);
        }
           
    }
}
