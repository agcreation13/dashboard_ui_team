<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsSampleExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'Sample Product',
                'Electronics',
                'SKU001',
                100.00,
                150.00,
                50,
                'pcs',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Category Name',
            'SKU',
            'Purchase Price',
            'Selling Price',
            'Quantity',
            'Unit',
        ];
    }
}

