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
                // Validate required fields
                $productName = isset($row['product_name']) ? trim($row['product_name']) : '';
                if (empty($productName)) {
                    $this->errors[] = "Row {$rowNumber}: Product Name is required. Skipping.";
                    $rowNumber++;
                    continue;
                }

                // Find category
                $categoryName = isset($row['category_name']) ? trim($row['category_name']) : '';
                if (empty($categoryName)) {
                    $this->errors[] = "Row {$rowNumber}: Category Name is required. Skipping.";
                    $rowNumber++;
                    continue;
                }
                
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

                // Validate numeric fields
                $purchasePrice = isset($row['purchase_price']) ? (float) $row['purchase_price'] : 0;
                $sellingPrice = isset($row['selling_price']) ? (float) $row['selling_price'] : 0;
                
                if ($purchasePrice < 0) {
                    $this->errors[] = "Row {$rowNumber}: Purchase Price must be >= 0. Skipping.";
                    $rowNumber++;
                    continue;
                }
                
                if ($sellingPrice < 0) {
                    $this->errors[] = "Row {$rowNumber}: Selling Price must be >= 0. Skipping.";
                    $rowNumber++;
                    continue;
                }

                // Validate GST percentage if provided
                $gstPercentage = isset($row['gst_percentage']) && !empty($row['gst_percentage']) ? (float) $row['gst_percentage'] : 0;
                if ($gstPercentage < 0 || $gstPercentage > 100) {
                    $this->errors[] = "Row {$rowNumber}: GST Percentage must be between 0 and 100. Skipping.";
                    $rowNumber++;
                    continue;
                }

                // Create product
                Product::create([
                    'name' => $productName,
                    'category_id' => $category->id,
                    'sku' => $sku,
                    'hsn' => isset($row['hsn']) && !empty(trim($row['hsn'])) ? trim($row['hsn']) : null,
                    'pack' => isset($row['pack']) && !empty(trim($row['pack'])) ? trim($row['pack']) : null,
                    'purchase_price' => $purchasePrice,
                    'selling_price' => $sellingPrice,
                    'mrp' => isset($row['mrp']) && !empty($row['mrp']) ? (float) $row['mrp'] : null,
                    'gst_percentage' => $gstPercentage,
                    'quantity' => isset($row['quantity']) ? max(0, (int) $row['quantity']) : 0,
                    'unit' => isset($row['unit']) && !empty(trim($row['unit'])) ? trim($row['unit']) : 'pcs',
                    'status' => isset($row['status']) && in_array(strtolower(trim($row['status'])), ['active', 'inactive']) ? strtolower(trim($row['status'])) : 'active',
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

