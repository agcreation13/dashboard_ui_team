@extends('layouts.app')

@section('main_content')
<div class="page-header">
   <div class="row pb-2">
            <div class="col-md-6 col-left">
                <div class="title">
                    <h4>Labour Role Details</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/master-entry/labour-roles') }}" >Labour Role</a></li>
                        <li class="breadcrumb-item"><a href="#" class="text-primary">Add</a></li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 text-right col-right">
              
                <a href="{{ url('/master-entry/labour-roles') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
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


    <form action="{{ route('labourRoles.Store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Role Title <sup class="text-danger">*</sup></label>
                <input type="hidden" name="parent_id" value="0">
                <input class="form-control" type="text" name="name" value="{{ old('name') }}" required placeholder="Role Title">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        </div>
    </form>
</div>
@endsection
