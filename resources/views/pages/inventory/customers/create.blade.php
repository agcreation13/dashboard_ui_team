@extends('layouts.app')

@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left">
            <div class="title">
                <h4>Add Customer</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-primary">Add</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right">
            <a href="{{ route('customers.index') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
                <i class="dw dw-return1"></i> <span class="back_title">Back</span>
            </a>
        </div>
    </div>
    <hr class="pb-2">

    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Customer Name <sup class="text-danger">*</sup></label>
                <input class="form-control" type="text" name="name" value="{{ old('name') }}" required placeholder="Enter customer name">
                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>Phone Number</label>
                <input class="form-control" type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Enter email">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-12 form-group">
                <label>Address</label>
                <textarea class="form-control" name="address" rows="3" placeholder="Enter address">{{ old('address') }}</textarea>
                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>GSTIN</label>
                <input class="form-control" type="text" name="gstin" value="{{ old('gstin') }}" placeholder="Enter GSTIN">
                @error('gstin') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-6 form-group">
                <label>State</label>
                <input class="form-control" type="text" name="state" value="{{ old('state') }}" placeholder="Enter state">
                @error('state') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary btn-sm mr-2" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;">Submit</button>
                <button type="reset" class="btn btn-secondary btn-sm" style="font-size: 14px; font-weight: 500; min-width: 100px; padding: 8px 20px;">Reset</button>
            </div>
        </div>
    </form>
</div>
@endsection

