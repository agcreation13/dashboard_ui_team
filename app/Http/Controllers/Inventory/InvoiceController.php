<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with('customer');

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderBy('created_at', 'desc')->get();
        return view('pages.inventory.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', 'active')->with('category')->get();
        $customers = Customer::orderBy('name')->get();
        return view('pages.inventory.invoices.create', compact('products', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'eway_bill' => 'nullable|string|max:100',
            'mr_no' => 'nullable|string|max:100',
            's_man' => 'nullable|string|max:100',
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'customer_address' => 'nullable|string',
            'customer_gstin' => 'nullable|string|max:50',
            'customer_state' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.hsn' => 'nullable|string|max:50',
            'items.*.pack' => 'nullable|string|max:100',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.free_quantity' => 'nullable|integer|min:0',
            'items.*.mrp' => 'nullable|numeric|min:0',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.gst_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.gst_amount' => 'nullable|numeric|min:0',
            'items.*.net_amount' => 'nullable|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'cgst_percentage' => 'nullable|numeric|min:0|max:100',
            'cgst_amount' => 'nullable|numeric|min:0',
            'sgst_percentage' => 'nullable|numeric|min:0|max:100',
            'sgst_amount' => 'nullable|numeric|min:0',
            'additional_amount' => 'nullable|numeric',
            'round_off' => 'nullable|numeric',
            'grand_total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 5, '0', STR_PAD_LEFT);

            // Get or create customer
            if ($request->customer_id) {
                $customer = Customer::findOrFail($request->customer_id);
                // Update customer with new information if provided
                $customer->update([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_mobile,
                    'email' => $request->customer_email,
                    'address' => $request->customer_address,
                    'gstin' => $request->customer_gstin,
                    'state' => $request->customer_state,
                ]);
            } else {
                // Create new customer
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_mobile,
                    'email' => $request->customer_email,
                    'address' => $request->customer_address,
                    'gstin' => $request->customer_gstin,
                    'state' => $request->customer_state,
                ]);
            }

            // Get default seller details (can be moved to config or settings table)
            $sellerDetails = $this->getDefaultSellerDetails();

            // Calculate total tax (CGST + SGST)
            $totalTax = ($request->cgst_amount ?? 0) + ($request->sgst_amount ?? 0);

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $request->invoice_date,
                'seller_name' => $sellerDetails['name'],
                'seller_address' => $sellerDetails['address'],
                'seller_email' => $sellerDetails['email'],
                'seller_phone' => $sellerDetails['phone'],
                'seller_gstin' => $sellerDetails['gstin'],
                'eway_bill' => $request->eway_bill,
                'mr_no' => $request->mr_no,
                's_man' => $request->s_man,
                'customer_id' => $customer->id,
                'customer_name' => $request->customer_name,
                'customer_mobile' => $request->customer_mobile,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'customer_gstin' => $request->customer_gstin,
                'customer_state' => $request->customer_state,
                'subtotal' => $request->subtotal,
                'discount' => 0, // Not used in GST format
                'tax' => $totalTax,
                'cgst_percentage' => $request->cgst_percentage ?? 0,
                'cgst_amount' => $request->cgst_amount ?? 0,
                'sgst_percentage' => $request->sgst_percentage ?? 0,
                'sgst_amount' => $request->sgst_amount ?? 0,
                'additional_amount' => $request->additional_amount ?? 0,
                'round_off' => $request->round_off ?? 0,
                'grand_total' => $request->grand_total,
                'status' => 'active',
            ]);

            // Create invoice items and update stock
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Check stock availability
                if ($product->quantity < $item['quantity']) {
                    DB::rollBack();
                    return redirect()->back()
                                     ->with('bg-color', 'danger')
                                     ->with('success', "Insufficient stock for {$product->name}. Available: {$product->quantity}")
                                     ->withInput();
                }

                // Calculate line total (net amount)
                $lineTotal = $item['net_amount'] ?? 0;

                // Create invoice item
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $product->name,
                    'hsn' => $item['hsn'] ?? $product->hsn,
                    'pack' => $item['pack'] ?? $product->pack,
                    'quantity' => $item['quantity'],
                    'free_quantity' => $item['free_quantity'] ?? 0,
                    'mrp' => $item['mrp'] ?? $product->mrp,
                    'rate' => $item['rate'],
                    'discount' => 0, // Not used, using discount_percentage
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'tax' => $item['gst_amount'] ?? 0,
                    'gst_percentage' => $item['gst_percentage'] ?? $product->gst_percentage ?? 0,
                    'gst_amount' => $item['gst_amount'] ?? 0,
                    'net_amount' => $item['net_amount'] ?? 0,
                    'line_total' => $lineTotal,
                ]);

                // Update product stock
                $product->quantity -= $item['quantity'];
                $product->save();
            }

            DB::commit();

            return redirect()->route('invoices.show', $invoice->id)
                             ->with('bg-color', 'success')
                             ->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('bg-color', 'danger')
                             ->with('success', 'Error creating invoice: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);
        return view('pages.inventory.invoices.show', compact('invoice'));
    }

    /**
     * Cancel invoice and restore stock
     */
    public function cancel($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        if ($invoice->status === 'cancelled') {
            return redirect()->route('invoices.index')
                             ->with('bg-color', 'warning')
                             ->with('success', 'Invoice is already cancelled.');
        }

        DB::beginTransaction();
        try {
            // Restore stock for each item
            foreach ($invoice->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }

            // Update invoice status
            $invoice->status = 'cancelled';
            $invoice->save();

            DB::commit();

            return redirect()->route('invoices.index')
                             ->with('bg-color', 'success')
                             ->with('success', 'Invoice cancelled and stock restored successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('invoices.index')
                             ->with('bg-color', 'danger')
                             ->with('success', 'Error cancelling invoice: ' . $e->getMessage());
        }
    }

    /**
     * Download PDF invoice
     */
    public function downloadPDF($id)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);
        
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $html = view('pages.inventory.invoices.pdf', compact('invoice'))->render();
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Print invoice view
     */
    public function print($id)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);
        return view('pages.inventory.invoices.print', compact('invoice'));
    }

    /**
     * Get default seller/company details
     * TODO: Move to config file or settings table for easy management
     */
    private function getDefaultSellerDetails()
    {
        // Default seller details - can be moved to config file or database settings table
        return [
            'name' => 'SIDDHI AYURVEDIC',
            'address' => 'MIDAS HEIGHTS, SECTOR-7, HDIL LAYOUT, VIRAR(W), PALGHAR-401303',
            'email' => 'siddhiayurvedic009@gmail.com',
            'phone' => '9021350010',
            'gstin' => '27BXFPP6045K1Z1',
        ];
    }
}
