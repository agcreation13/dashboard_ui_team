<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class InventoryController extends Controller
{
    /**
     * Display stock listing
     */
    public function stock(Request $request)
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->where('quantity', '<=', 10)->where('quantity', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('quantity', '<=', 0);
            } elseif ($request->stock_status === 'in_stock') {
                $query->where('quantity', '>', 0);
            }
        }

        $products = $query->orderBy('quantity', 'asc')->get();
        $categories = Category::where('status', 'active')->get();

        // Low stock warning count
        $lowStockCount = Product::where('quantity', '<=', 10)->where('quantity', '>', 0)->count();
        $outOfStockCount = Product::where('quantity', '<=', 0)->count();

        return view('pages.inventory.stock.index', compact('products', 'categories', 'lowStockCount', 'outOfStockCount'));
    }

    /**
     * Update stock manually
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->quantity = $request->quantity;
        $product->save();

        return redirect()->route('inventory.stock')
                         ->with('bg-color', 'success')
                         ->with('success', 'Stock updated successfully.');
    }
}
