@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Add Category</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary">Add</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ route('categories.index') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
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

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="row">
            {{-- Category Name --}}
            <div class="col-md-6 form-group">
                <label>Category Name <sup class="text-danger">*</sup></label>
                <input class="form-control" type="text" name="name" value="{{ old('name') }}" required placeholder="Enter category name">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Status --}}
            <div class="col-md-6 form-group">
                <label>Status <sup class="text-danger">*</sup></label>
                <select name="status" class="form-control" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Submit --}}
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary btn-sm mr-2" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;">Submit</button>
                <button type="reset" class="btn btn-secondary btn-sm" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;">Reset</button>
            </div>
        </div>
    </form>
</div>
@endsection

