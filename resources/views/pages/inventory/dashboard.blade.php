@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Inventory Dashboard</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.dashboard') }}" class="text-primary">Inventory Dashboard</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ url('/dashboard') }}" class="btn btn-sm border border-danger text-danger p-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">
                <i class="dw dw-return1"></i> Back
            </a>
        </div>
    </div>
    <hr class="pb-2">
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <!-- Total Products -->
    <div class="col-md-3">
        <div class="card" style="border-left: 4px solid #007bff;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Products</h6>
                        <h3 class="mb-0">{{ $totalProducts }}</h3>
                    </div>
                    <div class="text-primary" style="font-size: 40px;">
                        <i class="dw dw-box"></i>
                    </div>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-link p-0 mt-2">View All <i class="dw dw-right-arrow-1"></i></a>
            </div>
        </div>
    </div>

    <!-- Total Categories -->
    <div class="col-md-3">
        <div class="card" style="border-left: 4px solid #28a745;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Categories</h6>
                        <h3 class="mb-0">{{ $totalCategories }}</h3>
                    </div>
                    <div class="text-success" style="font-size: 40px;">
                        <i class="dw dw-folder"></i>
                    </div>
                </div>
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-link p-0 mt-2">View All <i class="dw dw-right-arrow-1"></i></a>
            </div>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="col-md-3">
        <div class="card" style="border-left: 4px solid #ffc107;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Customers</h6>
                        <h3 class="mb-0">{{ $totalCustomers }}</h3>
                    </div>
                    <div class="text-warning" style="font-size: 40px;">
                        <i class="dw dw-user"></i>
                    </div>
                </div>
                <a href="{{ route('customers.index') }}" class="btn btn-sm btn-link p-0 mt-2">View All <i class="dw dw-right-arrow-1"></i></a>
            </div>
        </div>
    </div>

    <!-- Total Invoices -->
    <div class="col-md-3">
        <div class="card" style="border-left: 4px solid #dc3545;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Invoices</h6>
                        <h3 class="mb-0">{{ $totalInvoices }}</h3>
                    </div>
                    <div class="text-danger" style="font-size: 40px;">
                        <i class="dw dw-file"></i>
                    </div>
                </div>
                <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-link p-0 mt-2">View All <i class="dw dw-right-arrow-1"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Stock Status Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #17a2b8;">
            <div class="card-body">
                <h6 class="text-muted mb-1">In Stock</h6>
                <h3 class="mb-0 text-info">{{ $inStockCount }}</h3>
                <small class="text-muted">Products with good stock</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #ffc107;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Low Stock</h6>
                <h3 class="mb-0 text-warning">{{ $lowStockCount }}</h3>
                <small class="text-muted">Products need restocking</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card" style="border-left: 4px solid #dc3545;">
            <div class="card-body">
                <h6 class="text-muted mb-1">Out of Stock</h6>
                <h3 class="mb-0 text-danger">{{ $outOfStockCount }}</h3>
                <small class="text-muted">Products unavailable</small>
            </div>
        </div>
    </div>
</div>

<!-- Sales Statistics -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="dw dw-calendar"></i> Today's Sales</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Invoices</h6>
                        <h4 class="mb-0">{{ $todayInvoices }}</h4>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Total Sales</h6>
                        <h4 class="mb-0">₹{{ number_format($todaySales, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="dw dw-calendar-1"></i> This Month's Sales</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Invoices</h6>
                        <h4 class="mb-0">{{ $monthInvoices }}</h4>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Total Sales</h6>
                        <h4 class="mb-0">₹{{ number_format($monthSales, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Invoices & Top Products -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="dw dw-file"></i> Recent Invoices</h5>
            </div>
            <div class="card-body">
                @if($recentInvoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentInvoices as $invoice)
                                <tr>
                                    <td><a href="{{ route('invoices.show', $invoice->id) }}">{{ $invoice->invoice_number }}</a></td>
                                    <td>{{ $invoice->customer_name }}</td>
                                    <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                    <td>₹{{ number_format($invoice->grand_total, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $invoice->status == 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-link p-0 mt-2">View All Invoices <i class="dw dw-right-arrow-1"></i></a>
                @else
                    <p class="text-muted mb-0">No invoices found</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="dw dw-star"></i> Top Selling Products (This Month)</h5>
            </div>
            <div class="card-body">
                @if($topProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->total_quantity }}</td>
                                    <td>₹{{ number_format($product->total_amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No sales data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Stock Alerts -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="dw dw-alert"></i> Low Stock Products</h5>
            </div>
            <div class="card-body">
                @if($lowStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td><span class="badge badge-warning">{{ $product->quantity }} {{ $product->unit }}</span></td>
                                    <td><a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-link p-0">Update</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-link p-0 mt-2">View All Products <i class="dw dw-right-arrow-1"></i></a>
                @else
                    <p class="text-muted mb-0">No low stock products</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="dw dw-alert-1"></i> Out of Stock Products</h5>
            </div>
            <div class="card-body">
                @if($outOfStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outOfStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td><span class="badge badge-danger">0 {{ $product->unit }}</span></td>
                                    <td><a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-link p-0">Update</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-link p-0 mt-2">View All Products <i class="dw dw-right-arrow-1"></i></a>
                @else
                    <p class="text-muted mb-0">No out of stock products</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

