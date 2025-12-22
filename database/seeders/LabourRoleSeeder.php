<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabourRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $roles = ['Representative','Supervisor', 'Mason', 'Carpenter', 'Welder', 'Electrician', 'Plumber', 'Painter'];
                   
        for ($i = 0; $i < 8; $i++) {
            DB::table('labour_roles')->insert([
                'name' => $roles[$i],
                'parent_id' => '0', // Assuming no parent roles for simplicity
                'addedBy' => '1', 
            ]);
        }
    }
}
