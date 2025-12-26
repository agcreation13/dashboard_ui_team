<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display reports index
     */
    public function index()
    {
        return view('pages.inventory.reports.index');
    }

    /**
     * Product stock report
     */
    public function stockReport(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('name')->get();
        $categories = Category::where('status', 'active')->get();

        return view('pages.inventory.reports.stock', compact('products', 'categories'));
    }

    /**
     * Category-wise product report
     */
    public function categoryReport()
    {
        $categories = Category::with(['products' => function($query) {
            $query->orderBy('name');
        }])->where('status', 'active')->get();

        return view('pages.inventory.reports.category', compact('categories'));
    }

    /**
     * Invoice report (date-wise)
     */
    public function invoiceReport(Request $request)
    {
        $query = Invoice::with('customer');

        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $query->whereBetween('invoice_date', [$startDate, $endDate]);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->get();

        // Calculate totals
        $totalInvoices = $invoices->count();
        $totalAmount = $invoices->sum('grand_total');
        $totalDiscount = $invoices->sum('discount');
        $totalTax = $invoices->sum('tax');

        return view('pages.inventory.reports.invoice', compact('invoices', 'startDate', 'endDate', 'totalInvoices', 'totalAmount', 'totalDiscount', 'totalTax'));
    }

    /**
     * Sales summary report
     */
    public function salesSummary(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        // Total sales
        $totalSales = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
                             ->where('status', 'active')
                             ->sum('grand_total');

        // Total invoices
        $totalInvoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
                                ->where('status', 'active')
                                ->count();

        // Top selling products
        $topProducts = InvoiceItem::join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
                                  ->whereBetween('invoices.invoice_date', [$startDate, $endDate])
                                  ->where('invoices.status', 'active')
                                  ->select('invoice_items.product_name', DB::raw('SUM(invoice_items.quantity) as total_quantity'), DB::raw('SUM(invoice_items.line_total) as total_amount'))
                                  ->groupBy('invoice_items.product_name')
                                  ->orderBy('total_quantity', 'desc')
                                  ->limit(10)
                                  ->get();

        // Daily sales
        $dailySales = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
                             ->where('status', 'active')
                             ->select(DB::raw('DATE(invoice_date) as date'), DB::raw('COUNT(*) as count'), DB::raw('SUM(grand_total) as total'))
                             ->groupBy('date')
                             ->orderBy('date')
                             ->get();

        return view('pages.inventory.reports.sales', compact('totalSales', 'totalInvoices', 'topProducts', 'dailySales', 'startDate', 'endDate'));
    }
}
