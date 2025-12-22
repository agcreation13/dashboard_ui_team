@extends('layouts.app')

@section('main_content')
<div class="page-header">
   <div class="row pb-2">
            <div class="col-md-6 col-left">
                <div class="title">
                    <h4>User Details</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/master-entry/user-list') }}" >User List</a></li>
                        <li class="breadcrumb-item"><a href="#" class="text-primary">Add</a></li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 text-right col-right">
              
                <a href="{{ url('/master-entry/user-list') }}" class="back_button text-danger border btn-sm border-danger p-2 h4">
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


<form action="{{ route('Uers.Store') }}" method="POST">
    @csrf
    <div class="row">
        {{-- Name --}}
        <div class="col-md-6 form-group">
            <label>Labour List <sup class="text-danger">*</sup></label>
            <select name="emp_id" class="form-control text-capitalize">
                     <option>-- Select Labour --</option>
                @foreach ($labourList as $labour)
                  <option value="{{ $labour->id }}" {{ old('emp_id') == $labour->id ? 'selected' : '' }}>{{$labour->name}}</option>
                @endforeach
            </select>
            @error('emp_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
   
              {{-- Role --}}
        <div class="col-md-6 form-group">
            <label>Role <sup class="text-danger">*</sup></label>
            <select name="role" class="form-control text-capitalize" required>
                <option value="">-- Select Role --</option>
                @foreach ($roleList as $role)
                <option value="{{ $role->role_name }}" {{ old('role') == $role->role_name ? 'selected' : '' }}>{{$role->role_name}}</option>
                @endforeach
            </select>
            @error('role') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
      
        {{-- Email --}}
        <div class="col-md-6 form-group">
            <label>Email-ID <sup class="text-danger">*</sup></label>
            <input class="form-control" type="email" name="email" value="{{ old('email') }}" required placeholder="Enter email">
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Password Fields --}}
        <div class="col-md-6 form-group password-field">
            <label>Password</label>
            <input class="form-control" type="password" name="password" placeholder="Enter new password">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>


        
  

        {{-- Change Password Checkbox
        <div class="col-md-6 form-group">
            <label><input type="checkbox" name="changepassword" value="1" id="changePasswordCheckbox"> Change Password</label>
        </div> --}}


        {{-- <div class="col-md-6 form-group password-field">
            <label>Confirm Password</label>
            <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm password">
        </div> --}}

        {{-- Submit --}}
        <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
    </div>
</form>

</div>
@push('js')
    @push('scripts')
<script>
    document.getElementById('changePasswordCheckbox').addEventListener('change', function () {
        const show = this.checked;
        document.querySelectorAll('.password-field').forEach(function (el) {
            el.classList.toggle('d-none', !show);
        });
    });
</script>
@endpush

@endpush
@endsection
