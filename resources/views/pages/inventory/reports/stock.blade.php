@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Stock Report</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary">Stock Report</a></li>
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

    <form method="GET" action="{{ route('reports.stock') }}" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="category_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div id="site-grid"></div>
</div>

@php
    $paginationLimit = env('TABLE_PAGINATION_LIMIT', 5);

    $gridData = $products->map(function ($product, $index) {
        $stockClass = $product->quantity <= 0 ? 'text-danger' : ($product->quantity <= 10 ? 'text-warning' : 'text-success');
        return [
            e($index + 1),
            e($product->name),
            e($product->category->name ?? 'N/A'),
            e($product->sku),
            number_format($product->purchase_price, 2),
            number_format($product->selling_price, 2),
            '<span class="' . $stockClass . '"><strong>' . e($product->quantity) . ' ' . e($product->unit) . '</strong></span>',
            e($product->status),
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
            { name: "Product Name", sort: true },
            { name: "Category", sort: true },
            { name: "SKU", sort: true },
            { name: "Purchase Price", sort: true },
            { name: "Selling Price", sort: true },
            { name: "Stock", sort: true },
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

