<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsSampleExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return new Collection([
            [
                'Sample Product',
                'Electronics',
                'SKU001',
                '12345678',
                '1 Box',
                100.00,
                150.00,
                180.00,
                18.00,
                50,
                'pcs',
                'active',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Category Name',
            'SKU',
            'HSN',
            'Pack',
            'Purchase Price',
            'Selling Price',
            'MRP',
            'GST Percentage',
            'Quantity',
            'Unit',
            'Status',
        ];
    }
}

