@extends('layouts.app')
@section('main_content')
<div class="page-header">
    <div class="row pb-2">
        <div class="col-md-6 col-left-form">
            <div class="title">
                <h4>Labour Details</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('master-entry/labour') }}">Labour</a></li>
                    <li class="breadcrumb-item active text-primary">Add</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-right col-right-form">
            <a href="{{ url('/master-entry/labour') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
                <i class="dw dw-return1"></i> <span class="back_title">Back</span>
            </a>
        </div>
    </div>
    <hr class="pb-2">

    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please correct the errors below.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        {{-- Flash Success Message --}}
        @if (session('success'))
            <div class="alert alert-{{ session('bg-color') }} alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

    {{-- Labour Form --}}
    <div class="Form-Element mt-2">
        <form action="{{ route('labour.Store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="row">
                {{-- Labour Name --}}
                <div class="col-md-2 form-group">
                    <label>Labour-ID</label>
                    <input class="form-control" readonly type="text" name="labour_id" value="{{ old('labour_id',$labour_id) }}" placeholder="Labour Name" >
                    @error('labour_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label>Labour Name <sup class="text-danger">*</sup></label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Labour Name" >
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Phone No --}}
                <div class="col-md-3 form-group">
                    <label>Phone No <sup class="text-danger">*</sup></label>
                    <input class="form-control" type="text" name="phoneno" value="{{ old('phoneno') }}" placeholder="Phone No" >
                    @error('phoneno') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- aadhar no --}}
                <div class="col-md-3 form-group">
                    <label>Aadhar No <sup class="text-danger">*</sup></label>
                    <input class="form-control" type="text" name="aadhar_no" value="{{ old('aadhar_no') }}" placeholder="Aadhar No"
                        pattern="\d{12}" maxlength="12" minlength="12" title="Aadhar number must be exactly 12 digits" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,12);">
                    @error('aadhar_no') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Labour Type --}}
                <div class="col-md-6 form-group">
                    <label>Labour Type <sup class="text-danger">*</sup></label>
                    <select class="form-control" name="role" >
                        <option value="">Select Labour Type</option>
                        @foreach($labour_role as $role)
                            <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Daily Wage --}}
                <div class="col-md-3 form-group">
                    <label>Daily Wage (₹) <sup class="text-danger">*</sup></label>
                    <input class="form-control" type="number" name="dailywage" value="{{ old('dailywage') }}" placeholder="₹" >
                    @error('dailywage') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Status --}}
                <div class="col-md-3 form-group">
                    <label>Status <sup class="text-danger">*</sup></label>
                    <select class="form-control text-capitalize" name="status" >
                        <option value="">Select Status</option>
                        @foreach($statuslist as $status)
                            @if($status['title'] == 'deactivate')
                               <option disabled value="{{ $status['title'] }}" {{ old('status') == $status['title'] ? 'selected' : '' }}>
                                {{ ucfirst($status['title']) }}
                            </option>
                            @else
                                <option value="{{ $status['title'] }}" {{ old('status') == $status['title'] ? 'selected' : '' }}>
                                {{ ucfirst($status['title']) }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Submit & Reset --}}
                <div class="col-md-12 form-group text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
