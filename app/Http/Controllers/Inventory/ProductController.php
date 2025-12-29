<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Traits\AuditLogTrait;

class ProductController extends Controller
{
    use AuditLogTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by stock
        if ($request->has('stock_filter') && $request->stock_filter) {
            if ($request->stock_filter === 'low') {
                $query->where('quantity', '<=', 10);
            } elseif ($request->stock_filter === 'out') {
                $query->where('quantity', '<=', 0);
            } elseif ($request->stock_filter === 'in_stock') {
                $query->where('quantity', '>', 0);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->get();
        $categories = Category::where('status', 'active')->get();

        // Low stock warning count
        $lowStockCount = Product::where('quantity', '<=', 10)->where('quantity', '>', 0)->count();
        $outOfStockCount = Product::where('quantity', '<=', 0)->count();

        return view('pages.inventory.products.index', compact('products', 'categories', 'lowStockCount', 'outOfStockCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        return view('pages.inventory.products.create', compact('categories'));
    }

    /**
     * Generate SKU automatically
     * Format: SKU-{number}-{product_3_letters}-{category_3_letters}-{random_2-3_letters}
     */
    private function generateSKU($productName, $categoryId)
    {
        // Get the next sequential number
        $lastProduct = Product::orderBy('id', 'desc')->first();
        $nextNumber = $lastProduct ? ($lastProduct->id + 1) : 1;
        $numberPart = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        
        // Get first 3 letters of product name (uppercase, remove spaces and special chars)
        $productNameClean = preg_replace('/[^a-zA-Z0-9]/', '', $productName);
        if (strlen($productNameClean) >= 3) {
            $productPart = strtoupper(substr($productNameClean, 0, 3));
        } else {
            // If product name is too short, use random 3 characters
            $productPart = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 3));
        }
        
        // Get category name 3 letters
        $category = Category::find($categoryId);
        $categoryName = $category ? $category->name : 'CAT';
        $categoryNameClean = preg_replace('/[^a-zA-Z0-9]/', '', $categoryName);
        if (strlen($categoryNameClean) >= 3) {
            $categoryPart = strtoupper(substr($categoryNameClean, 0, 3));
        } else {
            // If category name is too short, use random 3 characters
            $categoryPart = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 3));
        }
        
        // Generate random 2-3 letter string
        $randomLength = rand(2, 3);
        $randomPart = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $randomLength));
        
        // Generate SKU: SKU-{number}-{product}-{category}-{random}
        $sku = 'SKU-' . $numberPart . '-' . $productPart . '-' . $categoryPart . '-' . $randomPart;
        
        // Ensure uniqueness
        $counter = 1;
        $originalSku = $sku;
        while (Product::where('sku', $sku)->exists()) {
            // If duplicate, regenerate random part
            $randomPart = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $randomLength));
            $sku = 'SKU-' . $numberPart . '-' . $productPart . '-' . $categoryPart . '-' . $randomPart;
            $counter++;
            // Safety check to avoid infinite loop
            if ($counter > 100) {
                $sku = $originalSku . '-' . time();
                break;
            }
        }
        
        return $sku;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|unique:products,sku',
            'hsn' => 'nullable|string|max:50',
            'pack' => 'nullable|string|max:100',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        
        // Auto-generate SKU if not provided
        if (empty($data['sku'])) {
            $data['sku'] = $this->generateSKU($data['name'], $data['category_id']);
        }

        $product = Product::create($data);

        // Log audit
        AuditLogTrait::logAction('create', $product, null, $product->toArray());

        return redirect()->route('products.index')
                         ->with('bg-color', 'success')
                         ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('pages.inventory.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('status', 'active')->get();
        return view('pages.inventory.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'hsn' => 'nullable|string|max:50',
            'pack' => 'nullable|string|max:100',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'gst_percentage' => 'nullable|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        $product = Product::findOrFail($id);
        $oldData = $product->toArray();
        
        $product->update($request->all());

        // Log audit
        $newData = $product->fresh()->toArray();
        AuditLogTrait::logAction('update', $product, $oldData, $newData);

        return redirect()->route('products.index')
                         ->with('bg-color', 'success')
                         ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Check if product is used in invoices
        if ($product->invoiceItems()->count() > 0) {
            return redirect()->route('products.index')
                             ->with('bg-color', 'danger')
                             ->with('success', 'Cannot delete product. It has been used in invoices.');
        }

        $product->delete();

        return redirect()->route('products.index')
                         ->with('bg-color', 'success')
                         ->with('success', 'Product deleted successfully.');
    }

    /**
     * Show import form
     */
    public function import()
    {
        return view('pages.inventory.products.import');
    }

    /**
     * Process CSV import (Excel compatible)
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        try {
            $errors = [];
            $successCount = 0;
            $rowNumber = 1;

            $file = $request->file('file');
            $handle = fopen($file->getRealPath(), 'r');
            
            // Skip BOM if present
            $bom = fread($handle, 3);
            if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
                rewind($handle);
            }

            // Read header row
            $headers = fgetcsv($handle);
            if (!$headers) {
                throw new \Exception('Invalid file format. Please check the file.');
            }

            // Normalize headers (remove spaces, convert to lowercase)
            $headerMap = [];
            foreach ($headers as $index => $header) {
                $normalized = strtolower(trim($header));
                $headerMap[$normalized] = $index;
            }

            // Process data rows
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }

                try {
                    // Get values by header name
                    $getValue = function($key) use ($row, $headerMap) {
                        $normalized = strtolower(trim($key));
                        $index = $headerMap[$normalized] ?? null;
                        return $index !== null && isset($row[$index]) ? trim($row[$index]) : '';
                    };

                    // Validate required fields
                    $productName = $getValue('Product Name');
                    if (empty($productName)) {
                        $errors[] = "Row {$rowNumber}: Product Name is required. Skipping.";
                        continue;
                    }

                    $categoryName = $getValue('Category Name');
                    if (empty($categoryName)) {
                        $errors[] = "Row {$rowNumber}: Category Name is required. Skipping.";
                        continue;
                    }
                    
                    $category = Category::where('name', $categoryName)->first();
                    if (!$category) {
                        $errors[] = "Row {$rowNumber}: Category '{$categoryName}' not found. Skipping.";
                        continue;
                    }

                    $sku = $getValue('SKU');
                    if (empty($sku)) {
                        $errors[] = "Row {$rowNumber}: SKU is required. Skipping.";
                        continue;
                    }
                    
                    if (Product::where('sku', $sku)->exists()) {
                        $errors[] = "Row {$rowNumber}: SKU '{$sku}' already exists. Skipping.";
                        continue;
                    }

                    // Validate numeric fields
                    $purchasePrice = (float) $getValue('Purchase Price');
                    $sellingPrice = (float) $getValue('Selling Price');
                    
                    if ($purchasePrice < 0) {
                        $errors[] = "Row {$rowNumber}: Purchase Price must be >= 0. Skipping.";
                        continue;
                    }
                    
                    if ($sellingPrice < 0) {
                        $errors[] = "Row {$rowNumber}: Selling Price must be >= 0. Skipping.";
                        continue;
                    }

                    $gstPercentage = $getValue('GST Percentage');
                    $gstPercentage = !empty($gstPercentage) ? (float) $gstPercentage : 0;
                    if ($gstPercentage < 0 || $gstPercentage > 100) {
                        $errors[] = "Row {$rowNumber}: GST Percentage must be between 0 and 100. Skipping.";
                        continue;
                    }

                    // Create product
                    Product::create([
                        'name' => $productName,
                        'category_id' => $category->id,
                        'sku' => $sku,
                        'hsn' => $getValue('HSN') ?: null,
                        'pack' => $getValue('Pack') ?: null,
                        'purchase_price' => $purchasePrice,
                        'selling_price' => $sellingPrice,
                        'mrp' => $getValue('MRP') ? (float) $getValue('MRP') : null,
                        'gst_percentage' => $gstPercentage,
                        'quantity' => max(0, (int) $getValue('Quantity')),
                        'unit' => $getValue('Unit') ?: 'pcs',
                        'status' => in_array(strtolower($getValue('Status')), ['active', 'inactive']) ? strtolower($getValue('Status')) : 'active',
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }

            fclose($handle);

            $message = "Successfully imported {$successCount} product(s).";
            if (!empty($errors)) {
                $message .= " Errors: " . implode('; ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " and " . (count($errors) - 5) . " more.";
                }
            }

            return redirect()->route('products.index')
                             ->with('bg-color', $successCount > 0 ? 'success' : 'warning')
                             ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('products.import')
                             ->with('bg-color', 'danger')
                             ->with('success', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Export products to Excel
     */
    public function export(Request $request)
    {
        $categoryId = $request->get('category_id');
        $status = $request->get('status');
        $stockFilter = $request->get('stock_filter');

        $query = Product::with('category');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($stockFilter === 'low') {
            $query->where('quantity', '<=', 10);
        } elseif ($stockFilter === 'out') {
            $query->where('quantity', '<=', 0);
        } elseif ($stockFilter === 'in_stock') {
            $query->where('quantity', '>', 0);
        }

        $products = $query->get();

        $filename = 'products-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Excel XML header
            fwrite($file, '<?xml version="1.0"?>' . "\n");
            fwrite($file, '<?mso-application progid="Excel.Sheet"?>' . "\n");
            fwrite($file, '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n");
            fwrite($file, ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n");
            fwrite($file, ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n");
            fwrite($file, ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n");
            fwrite($file, ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n");
            
            // Styles
            fwrite($file, '<Styles>' . "\n");
            fwrite($file, '<Style ss:ID="Header">' . "\n");
            fwrite($file, '<Font ss:Bold="1"/>' . "\n");
            fwrite($file, '</Style>' . "\n");
            fwrite($file, '</Styles>' . "\n");
            
            // Worksheet
            fwrite($file, '<Worksheet ss:Name="Products">' . "\n");
            fwrite($file, '<Table>' . "\n");
            
            // Helper function to escape XML
            $escape = function($value) {
                return htmlspecialchars($value, ENT_XML1, 'UTF-8');
            };
            
            // Headers row with bold style
            fwrite($file, '<Row>' . "\n");
            $headers = ['SR No', 'Product Name', 'Category Name', 'SKU', 'HSN', 'Pack', 'Purchase Price', 'Selling Price', 'MRP', 'GST Percentage', 'Quantity', 'Unit', 'Status'];
            foreach ($headers as $header) {
                fwrite($file, '<Cell ss:StyleID="Header"><Data ss:Type="String">' . $escape($header) . '</Data></Cell>' . "\n");
            }
            fwrite($file, '</Row>' . "\n");

            // Data rows
            $srNo = 1;
            foreach ($products as $product) {
                fwrite($file, '<Row>' . "\n");
                
                $rowData = [
                    $srNo++,
                    $product->name,
                    $product->category->name ?? 'N/A',
                    $product->sku,
                    $product->hsn ?? '',
                    $product->pack ?? '',
                    $product->purchase_price,
                    $product->selling_price,
                    $product->mrp ?? '',
                    $product->gst_percentage ?? 0,
                    $product->quantity,
                    $product->unit,
                    $product->status,
                ];
                
                foreach ($rowData as $index => $value) {
                    $type = is_numeric($value) ? 'Number' : 'String';
                    fwrite($file, '<Cell><Data ss:Type="' . $type . '">' . $escape($value) . '</Data></Cell>' . "\n");
                }
                
                fwrite($file, '</Row>' . "\n");
            }
            
            fwrite($file, '</Table>' . "\n");
            fwrite($file, '</Worksheet>' . "\n");
            fwrite($file, '</Workbook>' . "\n");
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download sample CSV template (Excel compatible)
     */
    public function downloadSample()
    {
        $filename = 'products-sample-' . date('Y-m-d') . '.xls';
        
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Excel XML header
            fwrite($file, '<?xml version="1.0"?>' . "\n");
            fwrite($file, '<?mso-application progid="Excel.Sheet"?>' . "\n");
            fwrite($file, '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n");
            fwrite($file, ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n");
            fwrite($file, ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n");
            fwrite($file, ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n");
            fwrite($file, ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n");
            
            // Styles
            fwrite($file, '<Styles>' . "\n");
            fwrite($file, '<Style ss:ID="Header">' . "\n");
            fwrite($file, '<Font ss:Bold="1"/>' . "\n");
            fwrite($file, '</Style>' . "\n");
            fwrite($file, '</Styles>' . "\n");
            
            // Worksheet
            fwrite($file, '<Worksheet ss:Name="Sample">' . "\n");
            fwrite($file, '<Table>' . "\n");
            
            // Helper function to escape XML
            $escape = function($value) {
                return htmlspecialchars($value, ENT_XML1, 'UTF-8');
            };
            
            // Headers row with bold style
            fwrite($file, '<Row>' . "\n");
            $headers = ['SR No', 'Product Name', 'Category Name', 'SKU', 'HSN', 'Pack', 'Purchase Price', 'Selling Price', 'MRP', 'GST Percentage', 'Quantity', 'Unit', 'Status'];
            foreach ($headers as $header) {
                fwrite($file, '<Cell ss:StyleID="Header"><Data ss:Type="String">' . $escape($header) . '</Data></Cell>' . "\n");
            }
            fwrite($file, '</Row>' . "\n");

            // Sample data row
            fwrite($file, '<Row>' . "\n");
            $sampleData = [1, 'Sample Product', 'Electronics', 'SKU001', '12345678', '1 Box', 100.00, 150.00, 180.00, 18.00, 50, 'pcs', 'active'];
            foreach ($sampleData as $value) {
                $type = is_numeric($value) ? 'Number' : 'String';
                fwrite($file, '<Cell><Data ss:Type="' . $type . '">' . $escape($value) . '</Data></Cell>' . "\n");
            }
            fwrite($file, '</Row>' . "\n");
            
            fwrite($file, '</Table>' . "\n");
            fwrite($file, '</Worksheet>' . "\n");
            fwrite($file, '</Workbook>' . "\n");
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Update product stock
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $oldQuantity = $product->quantity;
        $product->quantity = $request->quantity;
        $product->save();

        $message = "Stock updated successfully! Product: {$product->name} | Previous: {$oldQuantity} {$product->unit} | New: {$product->quantity} {$product->unit}";

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->quantity,
                    'unit' => $product->unit
                ]
            ]);
        }

        return redirect()->route('products.index')
                         ->with('bg-color', 'success')
                         ->with('success', $message);
    }
}
