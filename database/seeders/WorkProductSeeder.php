<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkProduct;
use Illuminate\Support\Facades\DB;

class WorkProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workProducts = [
            [
                'work_product_name' => 'Cement',
                'rate' => 420.00,
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_product_name' => 'Sand',
                'rate' => 45.00,
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_product_name' => 'Bricks',
                'rate' => 8.50,
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_product_name' => 'Steel Rod',
                'rate' => 65.00,
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_product_name' => 'Paint',
                'rate' => 350.00,
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_product_name' => 'Tiles',
                'rate' => 35.00,
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_product_name' => 'Electrical Wire',
                'rate' => 25.00,
                'status' => 'active',
                'addedby' => '1',
            ],
            [
                'work_product_name' => 'Pipe',
                'rate' => 120.00,
                'status' => 'active',
                'addedby' => '1',
            ],
        ];

        foreach ($workProducts as $workProduct) {
            WorkProduct::create($workProduct);
        }
    }
}

