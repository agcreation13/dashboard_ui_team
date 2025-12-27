@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Add Product</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary">Add</a></li>
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

    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Product Name <sup class="text-danger">*</sup></label>
                <input class="form-control" type="text" name="name" value="{{ old('name') }}" required placeholder="Enter product name">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>Category <sup class="text-danger">*</sup></label>
                <input class="form-control" type="hidden" readonly name="sku" id="sku_input" value="{{ old('sku') }}" placeholder="Leave empty for auto-generation">
                <select name="category_id" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

           

            <div class="col-md-6 form-group">
                <label>HSN Code</label>
                <input class="form-control" type="text" name="hsn" value="{{ old('hsn') }}" placeholder="Enter HSN Code">
                @error('hsn') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-3 form-group">
                <label>PACK</label>
                <input class="form-control" type="text" name="pack" value="{{ old('pack') }}" placeholder="e.g., 60 cap, 1 kg, etc.">
                @error('pack') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-3 form-group">
                <label>Unit <sup class="text-danger">*</sup></label>
                <input class="form-control" type="text" name="unit" value="{{ old('unit', 'pcs') }}" required placeholder="pcs, kg, box, etc.">
                @error('unit') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2 form-group">
                <label>Purchase Price <sup class="text-danger">*</sup></label>
                <input class="form-control" type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price') }}" required placeholder="0.00" min="0">
                @error('purchase_price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2 form-group">
                <label>Selling Price <sup class="text-danger">*</sup></label>
                <input class="form-control" type="number" step="0.01" name="selling_price" value="{{ old('selling_price') }}" required placeholder="0.00" min="0">
                @error('selling_price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2 form-group">
                <label>MRP</label>
                <input class="form-control" type="number" step="0.01" name="mrp" value="{{ old('mrp') }}" placeholder="0.00" min="0">
                @error('mrp') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2 form-group">
                <label>GST Percentage (%)</label>
                <input class="form-control" type="number" step="0.01" name="gst_percentage" value="{{ old('gst_percentage', 0) }}" placeholder="0.00" min="0" max="100">
                @error('gst_percentage') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2 form-group">
                <label>Quantity / Stock <sup class="text-danger">*</sup></label>
                <input class="form-control" type="number" name="quantity" value="{{ old('quantity', 0) }}" required placeholder="0" min="0">
                @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-2 form-group">
                <label>Status <sup class="text-danger">*</sup></label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary btn-sm mr-2" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;">Submit</button>
                <button type="reset" class="btn btn-secondary btn-sm" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;">Reset</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
<script>
    // Auto-generate SKU preview when product name and category are entered
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.querySelector('input[name="name"]');
        const categorySelect = document.querySelector('select[name="category_id"]');
        const skuInput = document.getElementById('sku_input');
        
        function updateSKUPreview() {
            if (!skuInput || skuInput.value.trim() !== '') {
                return; // Don't update if user has entered a value
            }
            
            const productName = nameInput ? nameInput.value.trim() : '';
            const categoryId = categorySelect ? categorySelect.value : '';
            const categoryName = categorySelect ? categorySelect.options[categorySelect.selectedIndex].text : '';
            
            if (productName.length >= 3 && categoryId) {
                // Get first 3 letters of product name
                const productPart = productName.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();
                // Get first 3 letters of category name
                const categoryPart = categoryName.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();
                
                if (productPart.length >= 3 && categoryPart.length >= 3) {
                    // Show preview (will be finalized on server with random part)
                    skuInput.placeholder = 'Will be: SKU-XX-' + productPart + '-' + categoryPart + '-XX';
                }
            }
        }
        
        if (nameInput) {
            nameInput.addEventListener('blur', updateSKUPreview);
            nameInput.addEventListener('input', updateSKUPreview);
        }
        
        if (categorySelect) {
            categorySelect.addEventListener('change', updateSKUPreview);
        }
    });
</script>
@endpush

