@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Edit Product</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary">Edit</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ route('products.index') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
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

    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Product Name <sup class="text-danger">*</sup></label>
                <input class="form-control" type="text" name="name" value="{{ old('name', $product->name) }}" required placeholder="Enter product name">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>Category <sup class="text-danger">*</sup></label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>SKU / Product Code <sup class="text-danger">*</sup></label>
                <input class="form-control" type="text" name="sku" value="{{ old('sku', $product->sku) }}" required placeholder="Enter SKU">
                @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>HSN Code</label>
                <input class="form-control" type="text" name="hsn" value="{{ old('hsn', $product->hsn) }}" placeholder="Enter HSN Code">
                @error('hsn') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>PACK</label>
                <input class="form-control" type="text" name="pack" value="{{ old('pack', $product->pack) }}" placeholder="e.g., 60 cap, 1 kg, etc.">
                @error('pack') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>Unit <sup class="text-danger">*</sup></label>
                <input class="form-control" type="text" name="unit" value="{{ old('unit', $product->unit) }}" required placeholder="pcs, kg, box, etc.">
                @error('unit') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>Purchase Price <sup class="text-danger">*</sup></label>
                <input class="form-control" type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required placeholder="0.00" min="0">
                @error('purchase_price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>Selling Price <sup class="text-danger">*</sup></label>
                <input class="form-control" type="number" step="0.01" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required placeholder="0.00" min="0">
                @error('selling_price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>MRP (Maximum Retail Price)</label>
                <input class="form-control" type="number" step="0.01" name="mrp" value="{{ old('mrp', $product->mrp) }}" placeholder="0.00" min="0">
                @error('mrp') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>GST Percentage (%)</label>
                <input class="form-control" type="number" step="0.01" name="gst_percentage" value="{{ old('gst_percentage', $product->gst_percentage ?? 0) }}" placeholder="0.00" min="0" max="100">
                @error('gst_percentage') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>Quantity / Stock <sup class="text-danger">*</sup></label>
                <input class="form-control" type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" required placeholder="0" min="0">
                @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>Status <sup class="text-danger">*</sup></label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary btn-sm mr-2" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;">Update</button>
                <button type="reset" class="btn btn-secondary btn-sm" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;">Reset</button>
            </div>
        </div>
    </form>
</div>
@endsection

