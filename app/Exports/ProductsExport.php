<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $categoryId;
    protected $status;
    protected $stockFilter;

    public function __construct($categoryId = null, $status = null, $stockFilter = null)
    {
        $this->categoryId = $categoryId;
        $this->status = $status;
        $this->stockFilter = $stockFilter;
    }

    public function collection()
    {
        $query = Product::with('category');

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->stockFilter === 'low') {
            $query->where('quantity', '<=', 10);
        } elseif ($this->stockFilter === 'out') {
            $query->where('quantity', '<=', 0);
        } elseif ($this->stockFilter === 'in_stock') {
            $query->where('quantity', '>', 0);
        }

        return $query->get();
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
            'Status',
        ];
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->category->name ?? 'N/A',
            $product->sku,
            $product->purchase_price,
            $product->selling_price,
            $product->quantity,
            $product->unit,
            $product->status,
        ];
    }
}

