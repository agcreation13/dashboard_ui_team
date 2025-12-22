<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SiteDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        $stages = ['Initial', 'Rates Shared', 'Visits', 'Sampling', 'Estimates', 'Final Discussion', 'Converted'];
        $faker = Faker::create();
        $types = ['Residential', 'Commercial', 'Industrial'];
        $statuses = ['active', 'standby', 'completed'];
        for ($i = 1; $i <= 10; $i++) {
            DB::table('site_details')->insert([
                'name' => $faker->company . ' Project',
                'email' => $faker->unique()->safeEmail,
                'phoneno' => $faker->numerify('9#########'),
                'site_type' => $faker->randomElement($types),
                'owner_name' => $faker->name,
                'designer_name' => $faker->name,
                'address' => $faker->address,
                'start_date' => $faker->date(),
                'project_id' => strtoupper($faker->bothify('PRJ-####-???')),
                'representative' => '1', // get data form labour table check leabour role representative
                'supervisor' => '2', // get data form labour table check leabour role supervisor
                'mukadam' => '3', // get data form labour table check leabour role mukadam
                'bill_value' => $faker->numberBetween(1000000, 5000000),
                'payment_received' => $faker->numberBetween(500000, 3000000),
                'next_payment_date' => $faker->dateTimeBetween('+10 days', '+1 month')->format('Y-m-d'),
                'standby' => $faker->boolean(20), // 20% chance true
                'standby_reason' => $faker->boolean(20) ? $faker->sentence : null,
                'addedBy' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
