@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Stock Management</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.stock') }}" class="text-primary">Stock</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            @if($lowStockCount > 0)
                <span class="badge badge-warning mr-2">Low Stock: {{ $lowStockCount }}</span>
            @endif
            @if($outOfStockCount > 0)
                <span class="badge badge-danger mr-2">Out of Stock: {{ $outOfStockCount }}</span>
            @endif
            <a href="{{ url('/dashboard') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
                <i class="dw dw-return1"></i> <span class="back_title">Back</span>
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
            <form method="GET" action="{{ route('inventory.stock') }}">
                <select name="category_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('inventory.stock') }}">
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                <select name="stock_status" class="form-control" onchange="this.form.submit()">
                    <option value="">All Stock</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock (≤10)</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                </select>
            </form>
        </div>
    </div>

    <div id="site-grid"></div>
</div>

@php
    $paginationLimit = env('TABLE_PAGINATION_LIMIT', 5);
    $csrf = csrf_token();

    $gridData = $products->map(function ($product, $index) use ($csrf) {
        $stockClass = $product->quantity <= 0 ? 'text-danger' : ($product->quantity <= 10 ? 'text-warning' : 'text-success');
        $updateUrl = route('inventory.stock.update', $product->id);

        $stockInput = <<<HTML
            <form action="{$updateUrl}" method="POST" style="display: inline-block;" onsubmit="return confirm('Update stock for {$product->name}?');">
                <input type="hidden" name="_token" value="{$csrf}">
                <input type="hidden" name="_method" value="PUT">
                <div class="input-group" style="width: 150px;">
                    <input type="number" name="quantity" class="form-control form-control-sm" value="{$product->quantity}" min="0" required>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </div>
                </div>
            </form>
        HTML;

        return [
            e($index + 1),
            e($product->name),
            e($product->category->name ?? 'N/A'),
            e($product->sku),
            '<span class="' . $stockClass . '"><strong>' . e($product->quantity) . ' ' . e($product->unit) . '</strong></span>',
            $stockInput,
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
            {
                name: "Current Stock",
                sort: true,
                formatter: cell => gridjs.html(cell)
            },
            {
                name: "Update Stock",
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
</script>
@endpush

