@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Category-wise Product Report</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary">Category Report</a></li>
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

    @foreach($categories as $category)
    <div class="card mb-3">
        <div class="card-header">
            <h5>{{ $category->name }} ({{ $category->products->count() }} products)</h5>
        </div>
        <div class="card-body">
            @if($category->products->count() > 0)
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->quantity }} {{ $product->unit }}</td>
                            <td>{{ number_format($product->selling_price, 2) }}</td>
                            <td><span class="badge badge-{{ $product->status == 'active' ? 'success' : 'secondary' }}">{{ $product->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No products in this category.</p>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection

