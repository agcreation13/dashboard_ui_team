<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $successCount = 0;

    public function collection(Collection $rows)
    {
        $rowNumber = 2; // Start from row 2 (row 1 is header)
        
        foreach ($rows as $row) {
            try {
                // Find category
                $categoryName = isset($row['category_name']) ? trim($row['category_name']) : '';
                $category = Category::where('name', $categoryName)->first();
                
                if (!$category) {
                    $this->errors[] = "Row {$rowNumber}: Category '{$categoryName}' not found. Skipping.";
                    $rowNumber++;
                    continue;
                }

                // Check if SKU already exists
                $sku = isset($row['sku']) ? trim($row['sku']) : '';
                if (empty($sku)) {
                    $this->errors[] = "Row {$rowNumber}: SKU is required. Skipping.";
                    $rowNumber++;
                    continue;
                }
                
                if (Product::where('sku', $sku)->exists()) {
                    $this->errors[] = "Row {$rowNumber}: SKU '{$sku}' already exists. Skipping.";
                    $rowNumber++;
                    continue;
                }

                // Create product
                Product::create([
                    'name' => isset($row['product_name']) ? trim($row['product_name']) : '',
                    'category_id' => $category->id,
                    'sku' => $sku,
                    'purchase_price' => isset($row['purchase_price']) ? (float) $row['purchase_price'] : 0,
                    'selling_price' => isset($row['selling_price']) ? (float) $row['selling_price'] : 0,
                    'quantity' => isset($row['quantity']) ? (int) $row['quantity'] : 0,
                    'unit' => isset($row['unit']) ? trim($row['unit']) : 'pcs',
                    'status' => 'active',
                ]);

                $this->successCount++;
            } catch (\Exception $e) {
                $this->errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
            
            $rowNumber++;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }
}

