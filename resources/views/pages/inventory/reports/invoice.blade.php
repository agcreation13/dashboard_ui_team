@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Invoice Report</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary">Invoice Report</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ route('reports.index') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
                <i class="dw dw-return1"></i> <span class="back_title">Back</span>
            </a>
        </div>
    </div>
    <hr class="pb-2">

    <form method="GET" action="{{ route('reports.invoice') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;">Filter</button>
            </div>
        </div>
    </form>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6>Total Invoices</h6>
                    <h4>{{ $totalInvoices }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6>Total Amount</h6>
                    <h4>{{ number_format($totalAmount, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6>Total Discount</h6>
                    <h4>{{ number_format($totalDiscount, 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6>Total Tax</h6>
                    <h4>{{ number_format($totalTax, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div id="site-grid"></div>
</div>

@php
    $paginationLimit = env('TABLE_PAGINATION_LIMIT', 5);

    $gridData = $invoices->map(function ($invoice, $index) {
        return [
            e($index + 1),
            e($invoice->invoice_number),
            e($invoice->invoice_date->format('Y-m-d')),
            e($invoice->customer_name),
            number_format($invoice->subtotal, 2),
            number_format($invoice->discount, 2),
            number_format($invoice->tax, 2),
            number_format($invoice->grand_total, 2),
            '<span class="badge badge-'.($invoice->status == 'active' ? 'success' : 'warning').'">'.e($invoice->status).'</span>',
        ];
    });
@endphp
@endsection

@push('js')
<script src="{{ asset('assets/table/js/tablenew.js') }}"></script>
<script>
    new gridjs.Grid({
        columns: [
            { name: "ID", sort: false },
            { name: "Invoice #", sort: true },
            { name: "Date", sort: true },
            { name: "Customer", sort: true },
            { name: "Subtotal", sort: true },
            { name: "Discount", sort: true },
            { name: "Tax", sort: true },
            { name: "Grand Total", sort: true },
            { name: "Status", sort: true }
        ],
        data: {!! json_encode($gridData) !!},
        search: true,
        pagination: {
            enabled: true,
            limit: {{ $paginationLimit }}
        },
        resizable: true
    }).render(document.getElementById("site-grid"));
</script>
@endpush

