<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventoryDashboardController extends Controller
{
    /**
     * Display the inventory dashboard
     */
    public function index()
    {
        // Total counts
        $totalProducts = Product::count();
        $totalCategories = Category::where('status', 'active')->count();
        $totalCustomers = Customer::count();
        $totalInvoices = Invoice::count();

        // Stock statistics
        $lowStockCount = Product::where('quantity', '<=', 10)->where('quantity', '>', 0)->count();
        $outOfStockCount = Product::where('quantity', '<=', 0)->count();
        $inStockCount = Product::where('quantity', '>', 10)->count();

        // Today's statistics
        $today = Carbon::today();
        $todayInvoices = Invoice::whereDate('invoice_date', $today)->count();
        $todaySales = Invoice::whereDate('invoice_date', $today)
                             ->where('status', 'active')
                             ->sum('grand_total');

        // This month's statistics
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $monthInvoices = Invoice::whereBetween('invoice_date', [$monthStart, $monthEnd])->count();
        $monthSales = Invoice::whereBetween('invoice_date', [$monthStart, $monthEnd])
                            ->where('status', 'active')
                            ->sum('grand_total');

        // Recent invoices (last 5)
        $recentInvoices = Invoice::with('customer')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

        // Top selling products (this month)
        $topProducts = InvoiceItem::join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                                 ->whereBetween('invoices.invoice_date', [$monthStart, $monthEnd])
                                 ->where('invoices.status', 'active')
                                 ->select('invoice_items.product_name', 
                                         DB::raw('SUM(invoice_items.quantity) as total_quantity'),
                                         DB::raw('SUM(invoice_items.net_amount) as total_amount'))
                                 ->groupBy('invoice_items.product_name')
                                 ->orderBy('total_quantity', 'desc')
                                 ->limit(5)
                                 ->get();

        // Low stock products
        $lowStockProducts = Product::where('quantity', '<=', 10)
                                   ->where('quantity', '>', 0)
                                   ->with('category')
                                   ->orderBy('quantity', 'asc')
                                   ->limit(5)
                                   ->get();

        // Out of stock products
        $outOfStockProducts = Product::where('quantity', '<=', 0)
                                    ->with('category')
                                    ->orderBy('name', 'asc')
                                    ->limit(5)
                                    ->get();

        // Active vs Cancelled invoices
        $activeInvoices = Invoice::where('status', 'active')->count();
        $cancelledInvoices = Invoice::where('status', 'cancelled')->count();

        return view('pages.inventory.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalCustomers',
            'totalInvoices',
            'lowStockCount',
            'outOfStockCount',
            'inStockCount',
            'todayInvoices',
            'todaySales',
            'monthInvoices',
            'monthSales',
            'recentInvoices',
            'topProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'activeInvoices',
            'cancelledInvoices'
        ));
    }
}
