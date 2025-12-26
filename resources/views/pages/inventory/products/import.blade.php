@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Import Products</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary">Import</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ route('products.sample') }}" class="btn btn-sm border border-warning text-warning p-2 mr-2" style="font-size: 14px; font-weight: 500; min-width: 140px;">Download Sample</a>
            <a href="{{ route('products.index') }}" class="btn btn-sm border border-danger text-danger p-2" style="font-size: 14px; font-weight: 500; min-width: 80px;">
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

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Upload Excel File</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Select Excel File <sup class="text-danger">*</sup></label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted">Supported formats: .xlsx, .xls, .csv (Max: 10MB)</small>
                            @error('file') <small class="text-danger d-block">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm mr-2" style="font-size: 14px; font-weight: 500; min-width: 140px; padding: 8px 20px;">Import Products</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Excel Format</h5>
                </div>
                <div class="card-body">
                    <p><strong>Required Columns:</strong></p>
                    <ul>
                        <li>Product Name</li>
                        <li>Category Name</li>
                        <li>SKU</li>
                        <li>Purchase Price</li>
                        <li>Selling Price</li>
                        <li>Quantity</li>
                        <li>Unit</li>
                    </ul>
                    <p class="text-muted"><small>Note: Category must exist in the system. SKU must be unique.</small></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

