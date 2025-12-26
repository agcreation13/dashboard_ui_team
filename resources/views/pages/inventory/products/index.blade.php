@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Products</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-primary">Products</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            @if(isset($lowStockCount) && $lowStockCount > 0)
                <span class="badge badge-warning mr-2">Low Stock: {{ $lowStockCount }}</span>
            @endif
            @if(isset($outOfStockCount) && $outOfStockCount > 0)
                <span class="badge badge-danger mr-2">Out of Stock: {{ $outOfStockCount }}</span>
            @endif
            <a href="{{ route('products.create') }}" class="btn btn-sm border border-primary text-primary p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">+ New</a>
            <a href="{{ route('products.import') }}" class="btn btn-sm border border-info text-info p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">Import</a>
            <a href="{{ route('products.export') }}" class="btn btn-sm border border-success text-success p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">Export</a>
            <a href="{{ route('products.sample') }}" class="btn btn-sm border border-warning text-warning p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">Sample</a>
            <a href="{{ url('/dashboard') }}" class="btn btn-sm border border-danger text-danger p-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">
                <i class="dw dw-return1"></i> Back
            </a>
        </div>
    </div>
    <hr class="pb-2">

    @if (session('success'))
        <div class="alert alert-{{ session('bg-color', 'success') }} alert-dismissible fade show" role="alert">
            <i class="dw dw-check-circle" style="font-size: 18px; margin-right: 8px;"></i>
            <strong>{{ session('bg-color') == 'success' ? 'Success!' : 'Info!' }}</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Filters --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <form method="GET" action="{{ route('products.index') }}">
                <select name="category_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="col-md-3">
            <form method="GET" action="{{ route('products.index') }}">
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </form>
        </div>
        <div class="col-md-3">
            <form method="GET" action="{{ route('products.index') }}">
                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <select name="stock_filter" class="form-control" onchange="this.form.submit()">
                    <option value="">All Stock</option>
                    <option value="low" {{ request('stock_filter') == 'low' ? 'selected' : '' }}>Low Stock (≤10)</option>
                    <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="in_stock" {{ request('stock_filter') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
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
        $editUrl = route('products.edit', $product->id);
        $deleteUrl = route('products.destroy', $product->id);
        $updateStockUrl = route('products.updateStock', $product->id);
        $stockClass = $product->quantity <= 0 ? 'text-danger' : ($product->quantity <= 10 ? 'text-warning' : 'text-success');

        $stockDisplay = '<span class="' . $stockClass . '"><strong>' . e($product->quantity) . ' ' . e($product->unit) . '</strong></span>';

        $stockInput = <<<HTML
            <form action="{$updateStockUrl}" method="POST" style="display: inline-block; margin: 0;" class="stock-update-form" data-product-name="{$product->name}">
                <input type="hidden" name="_token" value="{$csrf}">
                <input type="hidden" name="_method" value="PUT">
                <div class="input-group input-group-sm" style="width: 140px;">
                    <input type="number" name="quantity" class="form-control form-control-sm stock-quantity-input" value="{$product->quantity}" min="0" required style="font-weight: 500; text-align: center; border-radius: 4px 0 0 4px;">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-sm btn-primary stock-update-btn" title="Update Stock" style="padding: 4px 10px; border-radius: 0 4px 4px 0;">
                            <i class="dw dw-check"></i>
                        </button>
                    </div>
                </div>
            </form>
        HTML;

        $actions = <<<HTML
            <div class="btn-group">
                <a href="{$editUrl}" data-toggle="tooltip" data-placement="top" data-original-title="Edit" class="btn btn-outline-primary btn-sm"><i class="dw dw-edit-1"></i></a>
                <form action="{$deleteUrl}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    <input type="hidden" name="_token" value="{$csrf}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Delete" class="btn btn-outline-danger btn-sm"><i class="dw dw-delete-3"></i></button>
                </form>
            </div>
        HTML;

        return [
            e($index + 1),
            e($product->name),
            e($product->category->name ?? 'N/A'),
            e($product->sku),
            number_format($product->purchase_price, 2),
            number_format($product->selling_price, 2),
            $stockDisplay,
            $stockInput,
            e($product->status),
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
            { name: "Product Name", sort: true },
            { name: "Category", sort: true },
            { name: "SKU", sort: true },
            { name: "Purchase Price", sort: true },
            { name: "Selling Price", sort: true },
            {
                name: "Current Stock",
                sort: true,
                formatter: cell => gridjs.html(cell)
            },
            {
                name: "Update Stock",
                sort: false,
                formatter: cell => gridjs.html(cell),
                width: '180px'
            },
            { name: "Status", sort: true },
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

    // Handle stock update form submission with better UX
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('stock-update-form')) {
            e.preventDefault();
            const form = e.target;
            const productName = form.getAttribute('data-product-name');
            const quantityInput = form.querySelector('.stock-quantity-input');
            const newQuantity = quantityInput.value;
            const submitBtn = form.querySelector('.stock-update-btn');
            const originalHtml = submitBtn.innerHTML;
            
            // Show confirmation
            if (!confirm('Update stock for ' + productName + ' to ' + newQuantity + '?')) {
                return;
            }
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            
            // Create form data
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json();
                }
            })
            .then(data => {
                if (data && data.success) {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
                alert('An error occurred while updating stock. Please try again.');
            });
        }
    });
</script>
<style>
    .stock-update-form .input-group {
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border-radius: 4px;
    }
    .stock-update-form .stock-quantity-input:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        outline: none;
    }
    .stock-update-form .stock-update-btn {
        transition: all 0.2s ease;
        border: none;
    }
    .stock-update-form .stock-update-btn:hover:not(:disabled) {
        background-color: #0056b3;
        transform: scale(1.05);
    }
    .stock-update-form .stock-update-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .stock-update-form .stock-quantity-input {
        border-right: none;
    }
</style>
@endpush

