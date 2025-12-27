<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use App\Exports\ProductsSampleExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
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

        Product::create($data);

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
        $product->update($request->all());

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
     * Process Excel import
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new ProductsImport();
            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $errors = $import->getErrors();

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

        return Excel::download(
            new ProductsExport($categoryId, $status, $stockFilter),
            'products-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Download sample Excel template
     */
    public function downloadSample()
    {
        return Excel::download(
            new ProductsSampleExport(),
            'products-sample-' . date('Y-m-d') . '.xlsx'
        );
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
