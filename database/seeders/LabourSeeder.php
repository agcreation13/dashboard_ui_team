<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class LabourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // You can use the factory to create dummy data for the labour table
        // \App\Models\Labour::factory(10)->create();

        // Alternatively, you can manually insert data
        $faker = Faker::create();
        
        // get role from labourroles table
        $roleIds = DB::table('labour_roles')->pluck('id')->toArray();
       
        
        for ($i = 0; $i < 10; $i++) {
            $status = $faker->randomElement(['active', 'deactivate']);
            DB::table('labours')->insert([
                'labour_id' => 'EMP-' . $faker->unique()->numberBetween(1, 20),
                'name'      => $faker->name,
                'dailywage' => $faker->numberBetween(400, 800),
                'role'      => $faker->randomElement($roleIds),
                'email'     => $faker->unique()->safeEmail,
                'phoneno'   => $faker->numerify('##########'),
                'aadhar_no' => $faker->numerify('##########'),
                'addedBy'   => 1,
                'status'    => $status,
                'remark'    => $status === 'deactivate' ? 'labour not working xyz region' : null,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        
        }
        // Add more entries as needed
        
    }
}
