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
use App\Traits\AuditLogTrait;

class InvoiceController extends Controller
{
    use AuditLogTrait;
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
        // Validate customer_id or customer details for new customer
        $customerValidation = [];
        if ($request->filled('customer_id')) {
            $customerValidation['customer_id'] = 'required|exists:customers,id';
        } else {
            // If customer_id is empty, validate customer details for new customer
            $customerValidation['customer_name'] = 'required|string|max:255';
            $customerValidation['customer_mobile'] = 'nullable|string|max:20';
            $customerValidation['customer_email'] = 'nullable|email|max:255';
            $customerValidation['customer_address'] = 'nullable|string';
            $customerValidation['customer_gstin'] = 'nullable|string|max:50';
            $customerValidation['customer_state'] = 'nullable|string|max:100';
        }

        $request->validate(array_merge([
            'invoice_date' => 'required|date',
            'eway_bill' => 'nullable|string|max:100',
            'mr_no' => 'nullable|string|max:100',
            's_man' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.free_quantity' => 'nullable|integer|min:0',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.net_amount' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'cgst_percentage' => 'nullable|numeric|min:0|max:100',
            'cgst_amount' => 'nullable|numeric|min:0',
            'sgst_percentage' => 'nullable|numeric|min:0|max:100',
            'sgst_amount' => 'nullable|numeric|min:0',
            'additional_amount' => 'nullable|numeric',
            'round_off' => 'nullable|numeric',
            'grand_total' => 'required|numeric|min:0',
        ], $customerValidation));

        DB::beginTransaction();
        try {
            // Generate invoice number: INV-B{YY}{MM}{DD}-{random 2 digits}-{total_invoices + 1}
            $year = date('y'); // 2 digit year (25 for 2025)
            $month = date('m'); // 2 digit month (01-12)
            $date = date('d'); // 2 digit date (01-31)
            $datePart = $year . $month . $date; // e.g., 251228
            
            // Generate random 2 digits (00-99)
            $randomPart = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
            
            // Use total invoices + 1 for sequential number
            $totalInvoices = Invoice::count();
            $sequentialPart = $totalInvoices + 1;
            
            // Generate invoice number: INV-B251228-02-1
            $invoiceNumber = 'INV-B' . $datePart . '-' . $randomPart . '-' . $sequentialPart;
            
            // Ensure uniqueness - if duplicate exists, regenerate random part
            $counter = 0;
            while (Invoice::where('invoice_number', $invoiceNumber)->exists() && $counter < 100) {
                $randomPart = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
                $invoiceNumber = 'INV-B' . $datePart . '-' . $randomPart . '-' . $sequentialPart;
                $counter++;
            }
            
            // Final fallback if still duplicate - use timestamp
            if (Invoice::where('invoice_number', $invoiceNumber)->exists()) {
                $randomPart = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
                $timestamp = time();
                $invoiceNumber = 'INV-B' . $datePart . '-' . $randomPart . '-' . substr($timestamp, -3);
            }

            // Get or create customer
            if ($request->filled('customer_id')) {
                // Use existing customer
                $customer = Customer::findOrFail($request->customer_id);
            } else {
                // Create new customer from form data
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_mobile,
                    'email' => $request->customer_email,
                    'address' => $request->customer_address,
                    'gstin' => $request->customer_gstin,
                    'state' => $request->customer_state,
                    'status' => 'active',
                ]);
            }

            // Calculate total tax (CGST + SGST)
            $totalTax = ($request->cgst_amount ?? 0) + ($request->sgst_amount ?? 0);

            // Create invoice - seller details come from config, not stored in DB
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $request->invoice_date,
                'eway_bill' => $request->eway_bill,
                'mr_no' => $request->mr_no,
                's_man' => $request->s_man,
                'customer_id' => $customer->id, // Only store customer_id
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

                // Create invoice item - only store essential fields, get product data via relationship
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'], // Only store product_id
                    'quantity' => $item['quantity'],
                    'free_quantity' => $item['free_quantity'] ?? 0,
                    'rate' => $item['rate'],
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'net_amount' => $item['net_amount'] ?? 0,
                ]);

                // Update product stock
                $product->quantity -= $item['quantity'];
                $product->save();
            }

            // Log audit
            $invoiceData = $invoice->fresh()->toArray();
            $itemsData = $invoice->items->toArray();
            AuditLogTrait::logAction('create', $invoice, null, [
                'invoice' => $invoiceData,
                'items' => $itemsData
            ]);

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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoice = Invoice::with(['customer', 'items.product'])->findOrFail($id);
        
        // Don't allow editing cancelled invoices
        if ($invoice->status === 'cancelled') {
            return redirect()->route('invoices.show', $invoice->id)
                             ->with('bg-color', 'warning')
                             ->with('success', 'Cannot edit cancelled invoice.');
        }
        
        $products = Product::where('status', 'active')->with('category')->get();
        $customers = Customer::orderBy('name')->get();
        
        return view('pages.inventory.invoices.edit', compact('invoice', 'products', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        
        // Don't allow updating cancelled invoices
        if ($invoice->status === 'cancelled') {
            return redirect()->route('invoices.show', $invoice->id)
                             ->with('bg-color', 'warning')
                             ->with('success', 'Cannot update cancelled invoice.');
        }
        
        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'eway_bill' => 'nullable|string|max:100',
            'mr_no' => 'nullable|string|max:100',
            's_man' => 'nullable|string|max:100',
            'customer_id' => 'required|exists:customers,id', // Now required
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.free_quantity' => 'nullable|integer|min:0',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.net_amount' => 'required|numeric|min:0',
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
            // Store old data for audit log
            $oldInvoiceData = $invoice->toArray();
            $oldItemsData = $invoice->items->toArray();
            
            // Restore stock from old invoice items
            foreach ($invoice->items as $oldItem) {
                $product = Product::find($oldItem->product_id);
                if ($product) {
                    $product->quantity += $oldItem->quantity;
                    $product->save();
                }
            }
            
            // Get customer - customer_id is now required
            $customer = Customer::findOrFail($request->customer_id);

            $totalTax = ($request->cgst_amount ?? 0) + ($request->sgst_amount ?? 0);

            // Update invoice - seller details come from config, not stored in DB
            $invoice->update([
                'invoice_date' => $request->invoice_date,
                'eway_bill' => $request->eway_bill,
                'mr_no' => $request->mr_no,
                's_man' => $request->s_man,
                'customer_id' => $customer->id, // Only store customer_id
                'subtotal' => $request->subtotal,
                'discount' => 0,
                'tax' => $totalTax,
                'cgst_percentage' => $request->cgst_percentage ?? 0,
                'cgst_amount' => $request->cgst_amount ?? 0,
                'sgst_percentage' => $request->sgst_percentage ?? 0,
                'sgst_amount' => $request->sgst_amount ?? 0,
                'additional_amount' => $request->additional_amount ?? 0,
                'round_off' => $request->round_off ?? 0,
                'grand_total' => $request->grand_total,
            ]);

            // Delete old invoice items
            $invoice->items()->delete();

            // Create new invoice items and update stock
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
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'], // Only store product_id
                    'quantity' => $item['quantity'],
                    'free_quantity' => $item['free_quantity'] ?? 0,
                    'rate' => $item['rate'],
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'net_amount' => $item['net_amount'] ?? 0,
                ]);

                // Update product stock
                $product->quantity -= $item['quantity'];
                $product->save();
            }

            // Log audit
            $newInvoiceData = $invoice->fresh()->toArray();
            $newItemsData = $invoice->items->toArray();
            \App\Traits\AuditLogTrait::logAction('update', $invoice, [
                'invoice' => $oldInvoiceData,
                'items' => $oldItemsData
            ], [
                'invoice' => $newInvoiceData,
                'items' => $newItemsData
            ]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice->id)
                             ->with('bg-color', 'success')
                             ->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Invoice update error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                             ->with('bg-color', 'danger')
                             ->with('success', 'Error updating invoice: ' . $e->getMessage())
                             ->withInput();
        }
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

}
