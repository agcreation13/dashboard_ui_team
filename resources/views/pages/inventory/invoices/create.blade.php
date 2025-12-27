@extends('layouts.app')

@section('main_content')
<style>
    .item-row td {
        padding: 5px !important;
    }
    .card {
        margin-bottom: 15px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        padding: 12px 15px;
    }
    .card-header h5 {
        color: #2c3e50;
        font-weight: 600;
        margin: 0;
    }
    .card-body {
        padding: 10px;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .form-group label {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }
    .form-control {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 2px 12px;
        font-size: 14px;
        height: 30px;
    }
    .form-control:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
    }
    .table-responsive {
        margin-bottom: 0;
    }
    .text-danger {
        color: #e74c3c !important;
    }
     
  
   
    .product-select option {
        text-align: left;
    }
    .btn {
        margin: 0 5px;
    }
</style>
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Create GST Invoice</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary">Create</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ route('invoices.index') }}" class="btn btn-sm border border-danger text-danger p-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">
                <i class="dw dw-return1"></i> Back
            </a>
        </div>
    </div>
    <hr class="pb-2">

    <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
        @csrf

        <!-- Invoice Details -->
        <div class="card">
            <div class="card-header">
                <h5><i class="dw dw-file"></i> Invoice Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label>Invoice Date <sup class="text-danger">*</sup></label>
                        <input class="form-control" type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                        @error('invoice_date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3 form-group">
                        <label>E-way Bill</label>
                        <input class="form-control" type="text" name="eway_bill" value="{{ old('eway_bill') }}" placeholder="E-way Bill">
                        @error('eway_bill') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3 form-group">
                        <label>MR NO.</label>
                        <input class="form-control" type="text" name="mr_no" value="{{ old('mr_no') }}" placeholder="MR NO">
                        @error('mr_no') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3 form-group">
                        <label>S. MAN</label>
                        <input class="form-control" type="text" name="s_man" value="{{ old('s_man') }}" placeholder="S. MAN">
                        @error('s_man') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Buyer/Customer Details -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="dw dw-user"></i> Buyer/Customer Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">Customer <sup class="text-danger">*</sup></label>
                        <select name="customer_id" id="customer_id" class="form-control">
                            <option value="">Select Customer(IF NEW)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" 
                                    data-name="{{ $customer->name }}" 
                                    data-phone="{{ $customer->phone }}" 
                                    data-email="{{ $customer->email ?? '' }}" 
                                    data-address="{{ $customer->address }}"
                                    data-gstin="{{ $customer->gstin }}"
                                    data-state="{{ $customer->state ?? '' }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                        <!-- <small class="text-muted">Select existing customer or enter new customer details below</small> -->
                        @error('customer_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">Customer Name <sup class="text-danger">*</sup></label>
                        <input class="form-control" type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required placeholder="Name">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">Customer Mobile</label>
                        <input class="form-control" type="text" name="customer_mobile" id="customer_mobile" value="{{ old('customer_mobile') }}" placeholder="Mobile">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">Customer Email</label>
                        <input class="form-control" type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" placeholder="Email">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">Customer GSTIN</label>
                        <input class="form-control" type="text" name="customer_gstin" id="customer_gstin" value="{{ old('customer_gstin') }}" placeholder="GSTIN">
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">State</label>
                        <input class="form-control" type="text" name="customer_state" id="customer_state" value="{{ old('customer_state') }}" placeholder="State">
                    </div>
                    <div class="col-md-12 form-group">
                        <label class="font-weight-bold">Customer Address</label>
                        <textarea class="form-control" name="customer_address" id="customer_address" rows="2" placeholder="Address">{{ old('customer_address') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="card">
            <div class="card-header">
                <h5><i class="dw dw-shopping-cart"></i> Invoice Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="max-height: 500px; overflow-x: auto; overflow-y: auto;">
                    <table class="table table-bordered table-hover mb-0" id="items-table" style="font-size: 13px; min-width: 1400px;">
                        <thead class="thead-light" style="position: sticky; top: 0; z-index: 10; background-color: #f8f9fa;">
                            <tr>
                                <th class=" style="width: 50px; min-width: 50px;">#</th>
                                <th style="min-width: 280px; width: 280px;">Product Name</th>
                                <th class=" style="width: 100px; min-width: 100px;">PACK</th>
                                <th class=" style="width: 90px; min-width: 90px;">QTY</th>
                                <th class=" style="width: 80px; min-width: 80px;">FREE</th>
                                <th class=" style="width: 110px; min-width: 110px;">MRP</th>
                                <th class=" style="width: 110px; min-width: 110px;">RATE</th>
                                <th class=" style="width: 90px; min-width: 90px;">DIS%</th>
                                <th class=" style="width: 90px; min-width: 90px;">GST%</th>
                                <th class=" style="width: 110px; min-width: 110px;">G.AMT</th>
                                <th class=" style="width: 130px; min-width: 130px;">NET AMT</th>
                                <th class=" style="width: 80px; min-width: 80px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-container">
                            <tr class="item-row">
                                <td class= sr-no align-middle" style="vertical-align: middle;">1</td>
                                <td>
                                    <select name="items[0][product_id]" class="form-control form-control-sm product-select" required style="border: 1px solid #ddd; width: 100%; font-size: 12px;">
                                        <option value="">Select</option>
                                        @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                        data-name="{{ $product->name }}"
                                        data-hsn="{{ $product->hsn ?? '' }}"
                                        data-pack="{{ $product->pack ?? '' }}"
                                        data-mrp="{{ $product->mrp ?? 0 }}"
                                        data-rate="{{ $product->selling_price ?? 0 }}"
                                        data-gst="{{ $product->gst_percentage ?? 0 }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="items[0][hsn]" class="hsn-field" value="">
                                </td>
                                <td><input type="text" name="items[0][pack]" class="form-control form-control-sm pack" placeholder="PACK" style="border: 1px solid #ddd; width: 100%; font-size: 12px;"></td>
                                <td><input type="number" name="items[0][quantity]" class="form-control form-control-sm quantity" placeholder="Qty" min="1" required style="border: 1px solid #ddd; width: 100%; font-size: 12px; font-weight: 500;"></td>
                                <td><input type="number" name="items[0][free_quantity]" class="form-control form-control-sm free-qty" placeholder="Free" min="0" value="0" style="border: 1px solid #ddd; width: 100%; font-size: 12px;"></td>
                                <td><input type="number" step="0.01" name="items[0][mrp]" class="form-control form-control-sm mrp" placeholder="MRP" min="0" style="border: 1px solid #ddd; width: 100%; font-size: 12px;"></td>
                                <td><input type="number" step="0.01" name="items[0][rate]" class="form-control form-control-sm rate" placeholder="Rate" min="0" required style="border: 1px solid #ddd; width: 100%; font-size: 12px; font-weight: 500;"></td>
                                <td><input type="number" step="0.01" name="items[0][discount_percentage]" class="form-control form-control-sm discount-pct" placeholder="DIS%" min="0" value="0" style="border: 1px solid #ddd; width: 100%; font-size: 12px;"></td>
                                <td><input type="number" step="0.01" name="items[0][gst_percentage]" class="form-control form-control-sm gst-pct" placeholder="GST%" min="0" value="0" style="border: 1px solid #ddd; width: 100%; font-size: 12px; font-weight: 500;"></td>
                                <td><input type="number" step="0.01" name="items[0][gst_amount]" class="form-control form-control-sm gst-amt" placeholder="G.AMT" min="0" readonly style="border: 1px solid #ddd; background-color: #f8f9fa; width: 100%; font-size: 12px;"></td>
                                <td><input type="number" step="0.01" name="items[0][net_amount]" class="form-control form-control-sm net-amt" placeholder="NET AMT" min="0" readonly style="border: 1px solid #ddd; background-color: #f8f9fa; font-weight: bold; width: 100%; font-size: 12px; color: #28a745;"></td>
                                <td class= align-middle" style="vertical-align: middle;"><button type="button" class="btn btn-sm btn-danger remove-item" title="Remove" style="padding: 5px 10px; margin: 0 auto; display: block;"><i class="dw dw-delete-3"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-primary btn-sm" id="add-item" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;"><i class="dw dw-add"></i> Add</button>
                </div>
            </div>
        </div>

        <!-- Totals -->
        <div class="card">
            <div class="card-header">
                <h5><i class="dw dw-calculator"></i> Invoice Totals</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="number" step="0.01" name="subtotal" id="subtotal" class="form-control" readonly value="0" style="font-weight: bold; background-color: #f8f9fa;">
                        </div>
                        <div class="form-group">
                            <label>Additional</label>
                            <input type="number" step="0.01" name="additional_amount" id="additional_amount" class="form-control" value="0" min="0" placeholder="0.00">
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>CGST %</label>
                                <input type="number" step="0.01" name="cgst_percentage" id="cgst_percentage" class="form-control" value="0" min="0" max="100" placeholder="0.00">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>CGST Amount</label>
                                <input type="number" step="0.01" name="cgst_amount" id="cgst_amount" class="form-control" readonly value="0" style="background-color: #f8f9fa;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>SGST %</label>
                                <input type="number" step="0.01" name="sgst_percentage" id="sgst_percentage" class="form-control" value="0" min="0" max="100" placeholder="0.00">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>SGST Amount</label>
                                <input type="number" step="0.01" name="sgst_amount" id="sgst_amount" class="form-control" readonly value="0" style="background-color: #f8f9fa;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Round Off</label>
                            <input type="number" step="0.01" name="round_off" id="round_off" class="form-control" readonly value="0" style="background-color: #f8f9fa;">
                        </div>
                        <div class="form-group">
                            <label style="font-size: 16px; font-weight: 600;">Grand Total</label>
                            <input type="number" step="0.01" name="grand_total" id="grand_total" class="form-control" readonly value="0" style="font-weight: bold; font-size: 18px; background-color: #e7f3ff; border: 2px solid #4a90e2;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm mr-2" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;"><i class="dw dw-check"></i> Create</button>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;"><i class="dw dw-cancel"></i> Cancel</a>
        </div>
    </form>
</div>

@push('js')
<script>
let itemIndex = 1;

// Customer auto-fill
document.getElementById('customer_id').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    if (option.value && option.value !== '') {
        // Fill all customer fields from selected customer
        document.getElementById('customer_name').value = option.dataset.name || '';
        document.getElementById('customer_mobile').value = option.dataset.phone || '';
        document.getElementById('customer_email').value = option.dataset.email || '';
        document.getElementById('customer_address').value = option.dataset.address || '';
        document.getElementById('customer_gstin').value = option.dataset.gstin || '';
        document.getElementById('customer_state').value = option.dataset.state || '';
    } else {
        // Clear all fields when "Select Customer" is chosen
        document.getElementById('customer_name').value = '';
        document.getElementById('customer_mobile').value = '';
        document.getElementById('customer_email').value = '';
        document.getElementById('customer_address').value = '';
        document.getElementById('customer_gstin').value = '';
        document.getElementById('customer_state').value = '';
    }
});

// Add item
document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const templateRow = container.firstElementChild;
    const newRow = templateRow.cloneNode(true);
    
    // Update all input/select names with new index
    newRow.querySelectorAll('input, select').forEach(input => {
        if (input.name) {
            input.name = input.name.replace(/\[0\]/g, '[' + itemIndex + ']');
        }
        // Remove data-listener attribute to allow re-attachment
        input.removeAttribute('data-listener');
        
        if(input.type === 'hidden') {
            // Clear hidden HSN field
            if(input.classList.contains('hsn-field')) {
                input.value = '';
            }
        } else if(!input.classList.contains('sr-no')) {
            if(input.type === 'number' && !input.readOnly) {
                input.value = input.name.includes('free_quantity') ? '0' : '';
            } else if(input.type === 'text' && !input.readOnly) {
                input.value = '';
            } else if(input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            }
        }
    });
    
    // Update serial number
    const srNoCell = newRow.querySelector('.sr-no');
    if (srNoCell) {
        srNoCell.textContent = itemIndex + 1;
    }
    
    container.appendChild(newRow);
    itemIndex++;
    
    // Re-attach event listeners for ALL rows (including new one)
    attachEventListeners();
    updateSrNos();
});

// Remove item
document.addEventListener('click', function(e) {
    if(e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
        const removeBtn = e.target.classList.contains('remove-item') ? e.target : e.target.closest('.remove-item');
        const rows = document.querySelectorAll('.item-row');
        if(rows.length > 1) {
            removeBtn.closest('.item-row').remove();
            updateSrNos();
            calculateTotal();
        }
    }
});

function updateSrNos() {
    document.querySelectorAll('.item-row .sr-no').forEach((sr, index) => {
        sr.textContent = index + 1;
    });
}

function attachEventListeners() {
    // Remove all existing listeners by removing data-listener attribute first
    document.querySelectorAll('.product-select').forEach(select => {
        select.removeAttribute('data-listener');
    });
    
    // Product select change
    document.querySelectorAll('.product-select').forEach(select => {
        if (!select.hasAttribute('data-listener')) {
            select.setAttribute('data-listener', 'true');
            select.addEventListener('change', function() {
                const option = this.options[this.selectedIndex];
                const row = this.closest('.item-row');
                
                if (option && option.value && option.value !== '') {
                    // Auto-fill HSN from product (hidden field)
                    const hsnInput = row.querySelector('.hsn-field');
                    if (hsnInput) {
                        hsnInput.value = option.dataset.hsn || '';
                    }
                    
                    // Auto-fill PACK from product
                    const packInput = row.querySelector('.pack');
                    if (packInput) {
                        packInput.value = option.dataset.pack || '';
                    }
                    
                    // Auto-fill MRP from product
                    const mrpInput = row.querySelector('.mrp');
                    if (mrpInput) {
                        const mrpValue = parseFloat(option.dataset.mrp) || 0;
                        mrpInput.value = mrpValue > 0 ? mrpValue.toFixed(2) : '';
                    }
                    
                    // Auto-fill RATE from product (selling_price)
                    const rateInput = row.querySelector('.rate');
                    if (rateInput) {
                        const rateValue = parseFloat(option.dataset.rate) || 0;
                        rateInput.value = rateValue > 0 ? rateValue.toFixed(2) : '';
                    }
                    
                    // Auto-fill GST% from product
                    const gstInput = row.querySelector('.gst-pct');
                    if (gstInput) {
                        const gstValue = parseFloat(option.dataset.gst) || 0;
                        gstInput.value = gstValue > 0 ? gstValue.toFixed(2) : '0';
                    }
                    
                    // Set default quantity to 1 if empty
                    const qtyInput = row.querySelector('.quantity');
                    if (qtyInput && (!qtyInput.value || qtyInput.value === '0' || qtyInput.value === '')) {
                        qtyInput.value = '1';
                    }
                    
                    // Trigger input events to ensure calculations run
                    if (qtyInput) qtyInput.dispatchEvent(new Event('input', { bubbles: true }));
                    if (rateInput) rateInput.dispatchEvent(new Event('input', { bubbles: true }));
                    if (gstInput) gstInput.dispatchEvent(new Event('input', { bubbles: true }));
                    
                } else {
                    // Clear all fields when product is deselected
                    const hsnInput = row.querySelector('.hsn-field');
                    if (hsnInput) hsnInput.value = '';
                    
                    const packInput = row.querySelector('.pack');
                    if (packInput) packInput.value = '';
                    
                    const mrpInput = row.querySelector('.mrp');
                    if (mrpInput) mrpInput.value = '';
                    
                    const rateInput = row.querySelector('.rate');
                    if (rateInput) rateInput.value = '';
                    
                    const gstInput = row.querySelector('.gst-pct');
                    if (gstInput) gstInput.value = '0';
                    
                    const gstAmtField = row.querySelector('.gst-amt');
                    if (gstAmtField) gstAmtField.value = '0.00';
                    
                    const netAmtField = row.querySelector('.net-amt');
                    if (netAmtField) netAmtField.value = '0.00';
                }
                
                // Calculate item total after auto-fill
                calculateItemTotal(row);
            });
        }
    });

    // Quantity, rate, discount, GST change
    document.querySelectorAll('.quantity, .rate, .discount-pct, .gst-pct, .mrp, .free-qty').forEach(input => {
        if (!input.hasAttribute('data-listener')) {
            input.setAttribute('data-listener', 'true');
            input.addEventListener('input', function() {
                calculateItemTotal(this.closest('.item-row'));
            });
        }
    });
}

function calculateItemTotal(row) {
    const qty = parseFloat(row.querySelector('.quantity').value) || 0;
    const freeQty = parseFloat(row.querySelector('.free-qty').value) || 0;
    const rate = parseFloat(row.querySelector('.rate').value) || 0;
    const discountPct = parseFloat(row.querySelector('.discount-pct').value) || 0;
    const gstPct = parseFloat(row.querySelector('.gst-pct').value) || 0;
    
    // Calculate base amount: QTY * RATE
    const baseAmount = qty * rate;
    
    // Calculate discount amount
    const discountAmount = (baseAmount * discountPct) / 100;
    const amountAfterDiscount = baseAmount - discountAmount;
    
    // Calculate GST amount on amount after discount
    const gstAmount = (amountAfterDiscount * gstPct) / 100;
    
    // Net amount = Amount after discount + GST amount
    const netAmount = amountAfterDiscount + gstAmount;
    
    // Update fields
    const gstAmtField = row.querySelector('.gst-amt');
    const netAmtField = row.querySelector('.net-amt');
    
    if (gstAmtField) {
        gstAmtField.value = gstAmount.toFixed(2);
    }
    if (netAmtField) {
        netAmtField.value = netAmount.toFixed(2);
    }
    
    calculateTotal();
}

function calculateTotal() {
    let subtotal = 0; // Sum of net amounts (includes GST)
    let totalTaxableAmount = 0; // Amount before GST for calculating CGST/SGST
    
    // Calculate subtotal and taxable amount
    document.querySelectorAll('.item-row').forEach(row => {
        const netAmt = parseFloat(row.querySelector('.net-amt').value) || 0;
        subtotal += netAmt;
        
        // Calculate taxable amount (before GST) for this item
        const qty = parseFloat(row.querySelector('.quantity').value) || 0;
        const rate = parseFloat(row.querySelector('.rate').value) || 0;
        const discountPct = parseFloat(row.querySelector('.discount-pct').value) || 0;
        const baseAmount = qty * rate;
        const discountAmount = (baseAmount * discountPct) / 100;
        totalTaxableAmount += (baseAmount - discountAmount);
    });
    
    const additionalAmount = parseFloat(document.getElementById('additional_amount').value) || 0;
    const cgstPct = parseFloat(document.getElementById('cgst_percentage').value) || 0;
    const sgstPct = parseFloat(document.getElementById('sgst_percentage').value) || 0;
    
    // Calculate CGST and SGST on total taxable amount
    // These are typically half of the total GST rate
    const cgstAmount = (totalTaxableAmount * cgstPct) / 100;
    const sgstAmount = (totalTaxableAmount * sgstPct) / 100;
    
    // Grand total = subtotal (which already includes item GST) + additional amount
    // Note: If CGST/SGST are meant to be separate, adjust accordingly
    let grandTotal = subtotal + additionalAmount;
    
    // Round off
    const roundedTotal = Math.round(grandTotal);
    const roundOff = roundedTotal - grandTotal;
    grandTotal = roundedTotal;
    
    // Update fields
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('cgst_amount').value = cgstAmount.toFixed(2);
    document.getElementById('sgst_amount').value = sgstAmount.toFixed(2);
    document.getElementById('round_off').value = roundOff.toFixed(2);
    document.getElementById('grand_total').value = grandTotal.toFixed(2);
}

// Additional amount and GST percentage change
document.getElementById('additional_amount').addEventListener('input', calculateTotal);
document.getElementById('cgst_percentage').addEventListener('input', calculateTotal);
document.getElementById('sgst_percentage').addEventListener('input', calculateTotal);

attachEventListeners();
</script>
@endpush
@endsection
