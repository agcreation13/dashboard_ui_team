<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkArea;
use Illuminate\Support\Facades\DB;

class WorkAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workAreas = [
            [
                'work_area_name' => 'Square Feet',
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_area_name' => 'Running Feet',
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_area_name' => 'Cubic Feet',
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_area_name' => 'Square Meter',
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_area_name' => 'Number',
                'status' => 'active',
                'addedby' => '1',
            ],
        ];

        foreach ($workAreas as $workArea) {
            WorkArea::create($workArea);
        }
    }
}

