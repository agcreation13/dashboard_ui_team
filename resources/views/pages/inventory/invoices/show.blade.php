@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>GST Invoice #{{ $invoice->invoice_number }}</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">Invoices</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary">View</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            @if($invoice->status == 'active')
                <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm border border-primary text-primary p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 80px;"><i class="dw dw-edit-1"></i> Edit</a>
            @endif
            <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-sm border border-danger text-danger p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 80px;" target="_blank"><i class="dw dw-file-38"></i> PDF</a>
            <a href="{{ route('invoices.print', $invoice->id) }}" class="btn btn-sm border border-secondary text-secondary p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 80px;" target="_blank"><i class="dw dw-print"></i> Print</a>
            @if($invoice->status == 'active')
                <a href="{{ route('invoices.cancel', $invoice->id) }}" class="btn btn-sm border border-warning text-warning p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 80px;" onclick="return confirm('Are you sure? Stock will be restored.');"><i class="dw dw-cancel"></i> Cancel</a>
            @endif
            <a href="{{ route('invoices.index') }}" class="btn btn-sm border border-danger text-danger p-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">
                <i class="dw dw-return1"></i> Back
            </a>
        </div>
    </div>
    <hr class="pb-2">

    <div class="card">
        <div class="card-body">
            <!-- Header Section -->
            <div class="row mb-4" style="border: 1px solid #ddd; padding: 15px;">
                <div class="col-md-4">
                    <h5><strong>{{ config('seller.name') }}</strong></h5>
                    <p>{{ config('seller.address') }}</p>
                    <p>EMAIL:- {{ config('seller.email') }}</p>
                    <p>Phone no. :-{{ config('seller.phone') }}</p>
                    <p><strong>GSTIN:</strong> {{ config('seller.gstin') }}</p>
                </div>
                <div class="col-md-4 text-left">
                    <h4><strong>GST INVOICE</strong></h4>
                    <p><strong>Invoice No.:</strong> {{ $invoice->invoice_number }}</p>
                    <p><strong>Date:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                    <p><strong>E-way Bill:</strong> {{ $invoice->eway_bill ?? '' }}</p>
                    <p><strong>MR NO.:</strong> {{ $invoice->mr_no ?? '' }}</p>
                    <p><strong>S. MAN:</strong> {{ $invoice->s_man ?? '' }}</p>
                </div>
                <div class="col-md-4 text-right">
                    <h4>Buyer</h4>
                    <h5>Name: <strong>{{ $invoice->customer->name ?? 'N/A' }}</strong></h5>
                    <p>Address: {{ $invoice->customer->address ?? '' }}</p>
                    <p>Phone No.: {{ $invoice->customer->phone ?? '' }}</p>
                    <p>Email: {{ $invoice->customer->email ?? '' }}</p>
                    <p>GST NO.: {{ $invoice->customer->gstin ?? '' }}</p>
                </div>
            </div>

            <!-- Product Details Table -->
            <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                            <th class="text-center">Sr No.</th>
                            <th class="text-center">HSN</th>
                            <th>Product Name</th>
                            <th class="text-center">PACK</th>
                            <th class="text-center">QTY</th>
                            <th class="text-center">FREE</th>
                            <th class="text-right">MRP</th>
                            <th class="text-right">RATE</th>
                            <th class="text-right">DIS%</th>
                            <th class="text-right">GST%</th>
                            <th class="text-right">G.AMT</th>
                            <th class="text-right">NET AMT</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach($invoice->items as $index => $item)
                    <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $item->product->hsn ?? '' }}</td>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $item->product->pack ?? '' }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-center">{{ $item->free_quantity ?? 0 }}</td>
                            <td class="text-right">{{ $item->product->mrp ? number_format($item->product->mrp, 2) : '' }}</td>
                            <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                            <td class="text-right">{{ number_format($item->discount_percentage ?? 0, 2) }}%</td>
                            <td class="text-right">{{ number_format($item->product->gst_percentage ?? 0, 2) }}%</td>
                            <td class="text-right">{{ number_format((($item->rate * $item->quantity) * ($item->product->gst_percentage ?? 0) / 100), 2) }}</td>
                            <td class="text-right">{{ number_format($item->net_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>

            <!-- Summary Section -->
            <div class="row mt-4">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <table class="table table-bordered">
                        <tr>
                            <td><strong>CGST:</strong></td>
                            <td class="text-right">{{ number_format($invoice->cgst_percentage ?? 0, 2) }}%</td>
                        </tr>
                        <tr>
                            <td><strong>CGST AMT:</strong></td>
                            <td class="text-right">{{ number_format($invoice->cgst_amount ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>SGST:</strong></td>
                            <td class="text-right">{{ number_format($invoice->sgst_percentage ?? 0, 2) }}%</td>
                        </tr>
                        <tr>
                            <td><strong>SGST AMT:</strong></td>
                            <td class="text-right">{{ number_format($invoice->sgst_amount ?? 0, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>TOTALAM:</strong></td>
                            <td class="text-right">{{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                            <td><strong>TOTAL AMT:</strong></td>
                            <td class="text-right">{{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                            <td><strong>AD/LS AMT:</strong></td>
                            <td class="text-right">{{ number_format($invoice->additional_amount ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                            <td><strong>ROUND OFF:</strong></td>
                            <td class="text-right">{{ number_format($invoice->round_off ?? 0, 2) }}</td>
                        </tr>
                        <tr class="table-primary">
                            <td><strong>GRAND TOTAL:</strong></td>
                            <td class="text-right"><strong>{{ number_format($invoice->grand_total, 2) }}</strong></td>
                    </tr>
            </table>
                </div>
            </div>

            <!-- Status -->
            <div class="mt-3">
                <strong>Status:</strong> 
                <span class="badge badge-{{ $invoice->status == 'active' ? 'success' : 'warning' }}">{{ strtoupper($invoice->status) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
