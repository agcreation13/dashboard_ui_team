@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Invoices</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}" class="text-primary">Invoices</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ route('invoices.create') }}" class="btn btn-sm border border-primary text-primary p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 100px;">+ New Invoice</a>
            <a href="{{ url('/dashboard') }}" class="btn btn-sm border border-danger text-danger p-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">
                <i class="dw dw-return1"></i> Back
            </a>
        </div>
    </div>
    <hr class="pb-2">

    @if (session('success'))
        <div class="alert alert-{{ session('bg-color') }} alert-dismissible fade show">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <form method="GET" action="{{ route('invoices.index') }}">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" placeholder="Start Date">
            </form>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('invoices.index') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" placeholder="End Date">
            </form>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('invoices.index') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>
    </div>

    <div id="site-grid" class="table-responsive text-capitalize"></div>
</div>

@php
    $paginationLimit = env('TABLE_PAGINATION_LIMIT', 5);
    $csrf = csrf_token();

    $gridData = $invoices->map(function ($invoice, $index) use ($csrf) {
        $showUrl = route('invoices.show', $invoice->id);
        $pdfUrl = route('invoices.pdf', $invoice->id);
        $printUrl = route('invoices.print', $invoice->id);
        $cancelUrl = route('invoices.cancel', $invoice->id);

        $isCancelled = $invoice->status == 'cancelled';
        
        if($isCancelled) {
            // Disabled cancel button for cancelled invoices
            $cancelButton = <<<HTML
                <span data-toggle="tooltip" data-placement="top" data-original-title="Invoice already cancelled" class="btn btn-outline-danger btn-sm disabled" style="pointer-events: none; opacity: 0.6; cursor: not-allowed;"><i class="dw dw-cancel"></i></span>
        HTML;
        } else {
            // Active cancel button for active invoices
            $cancelButton = <<<HTML
                <a href="{$cancelUrl}" data-toggle="tooltip" data-placement="top" data-original-title="Cancel" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this invoice? Stock will be restored.');"><i class="dw dw-cancel"></i></a>
        HTML;
        }
        
        $actions = <<<HTML
            <div class="btn-group">
                <a href="{$showUrl}" data-toggle="tooltip" data-placement="top" data-original-title="View" class="btn btn-outline-info btn-sm"><i class="dw dw-eye"></i></a>
                <a href="{$pdfUrl}" data-toggle="tooltip" data-placement="top" data-original-title="PDF" class="btn btn-outline-primary btn-sm" target="_blank"><i class="dw dw-file-38"></i></a>
                <a href="{$printUrl}" data-toggle="tooltip" data-placement="top" data-original-title="Print" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="dw dw-print"></i></a>
                {$cancelButton}
            </div>
        HTML;

        return [
            e($index + 1),
            e($invoice->invoice_number),
            e($invoice->invoice_date->format('Y-m-d')),
            e($invoice->customer_name),
            number_format($invoice->grand_total, 2),
            '<span class="badge badge-'.($invoice->status == 'active' ? 'success' : 'warning').'">'.e($invoice->status).'</span>',
            $actions,
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
            { name: "Total", sort: true },
            {
                name: "Status",
                sort: true,
                formatter: cell => gridjs.html(cell)
            },
            {
                name: "Actions",
                sort: false,
                formatter: cell => gridjs.html(cell)
            }
        ],
        data: {!! json_encode($gridData) !!},
        search: true,
        pagination: {
            enabled: true,
            limit: {{ $paginationLimit }}
        },
        resizable: true
    }).render(document.getElementById("site-grid"));
    
    // Prevent clicks on disabled cancel buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn.disabled') || e.target.closest('a[style*="pointer-events: none"]')) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });
</script>
<style>
    .btn-group .btn.disabled,
    .btn-group a[style*="pointer-events: none"] {
        opacity: 0.5 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }
    .btn-group .btn.disabled:hover,
    .btn-group a[style*="pointer-events: none"]:hover {
        opacity: 0.5 !important;
    }
</style>
@endpush

