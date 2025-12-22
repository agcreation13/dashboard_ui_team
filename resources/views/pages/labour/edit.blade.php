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
                    <li class="breadcrumb-item"><a href="{{ url('/master-entry') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('master-entry/labour') }}">Labour</a></li>
                    <li class="breadcrumb-item active text-primary">Edit</li>
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
        <form action="{{ route('labour.Update', $labourDetail->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="row">
                {{-- Labour id --}}
                <div class="col-md-2 form-group">
                    <label>Labour-ID</label>
                    <input class="form-control" readonly type="text" name="labour_id" value="{{ old('labour_id',$labourDetail->labour_id) }}" placeholder="Labour Name" >
                    @error('labour_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- Labour Name --}}
                <div class="col-md-4 form-group">
                    <label>Labour Name <sup class="text-danger">*</sup></label>
                    <input class="form-control" type="text" name="name" value="{{ $labourDetail->name }}" placeholder="Labour Name" >
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- Phone No --}}
                <div class="col-md-3 form-group">
                    <label>Phone No <sup class="text-danger">*</sup></label>
                    <input class="form-control" type="text" name="phoneno" value="{{ $labourDetail->phoneno }}" placeholder="Phone No" >
                    @error('phoneno') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- Aadhar No --}}
                <div class="col-md-3 form-group">
                    <label>Aadhar No <sup class="text-danger">*</sup></label>
                    <input class="form-control" type="text" name="aadhar_no" value="{{ old('aadhar_no',$labourDetail->aadhar_no) }}" placeholder="Aadhar No"
                        pattern="\d{12}" maxlength="12" minlength="12" title="Aadhar number must be exactly 12 digits" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,12);">
                    @error('aadhar_no') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- Labour Type --}}
                <div class="col-md-6 form-group">
                    <label>Labour Type <sup class="text-danger">*</sup></label>
                    <select class="form-control" onchange="handleLabourTypeChange()" name="role" id="LabourType">
                        <option value="">Select Labour Type</option>
                        @foreach($labour_role as $role)
                            <option data-role="{{ $role->name }}" value="{{ $role->id }}" {{ $labourDetail->role == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- Daily Wage --}}
                <div class="col-md-3 form-group">
                    <label>Daily Wage (₹) <sup class="text-danger">*</sup></label>
                    <input class="form-control" type="number" name="dailywage" value="{{ $labourDetail->dailywage }}" placeholder="₹" >
                    @error('dailywage') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- Status --}}
                <div class="col-md-3 form-group">
                    <label>Status <sup class="text-danger">*</sup></label>
                    <select class="form-control text-capitalize" name="status" id="leadsStatus" >
                        <option value="">Select Status</option>
                        @foreach($statuslist as $status)
                            <option value="{{ $status['title'] }}" {{ $labourDetail->status == $status['title'] ? 'selected' : '' }}>{{ $status['title'] }}</option>
                        @endforeach
                    </select>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

            <div class="col-md-12" id="login_access_section" style="display: {{ $labourDetail->login_access == 'yes' ? 'block' : 'none' }};">
                <div class="row">
                {{-- Login Access --}}
                <div class="col-md-3 form-group">
                    <label>Login Access <sup class="text-danger">*</sup></label>
                    <select class="form-control text-capitalize" name="login_access" id="login_access" >
                        <option value="no" {{ $labourDetail->login_access == 'no' ? 'selected' : '' }}>No</option>
                        <option value="yes" {{ $labourDetail->login_access == 'yes' ? 'selected' : '' }}>Yes</option>
                      
                    </select>
                    @error('login_access') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- Email --}}
                <div class="col-md-3 form-group">
                    <label>Email-ID <sup class="text-danger">*</sup></label>
                    <input class="form-control" type="email" name="email" value="{{ old('email',$labourDetail->email) }}" placeholder="Email ID" >
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- Password --}}
                <div class="col-md-3 form-group">
                    <div class="input-group-append">
                        <label for="password">Password <sup class="text-danger">*</sup></label>
                        <span class="input-group-text text-primary mt-n2" onclick="showpassword()" style="cursor: pointer;">
                            <i class="dw dw-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                    <input 
                    class="form-control" 
                    type="password" 
                    id="password" 
                    name="password" 
                    value="{{ old('password', $labourDetail->password) }}" 
                    placeholder="Password"
                    >
                    
                </div>
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            
        </div>
    </div>
    
                {{-- Close Remark Box --}}
                <div class="col-md-6 form-group" id="closeRemarkBox" style="display: none;">
                    <label>Deactivate Remark <sup class="text-danger">*</sup></label>
                    <textarea class="form-control" name="remark" id="closeRemark" placeholder="Enter reason for deactivate">{{ old('close_remark') }}</textarea>
                    @error('remark') <small class="text-danger">{{ $message }}</small> @enderror
                    <small id="closeRemarkError" class="text-danger d-none">Please enter a remark for deactivate.</small>
                </div>
                @if($labourDetail->status === 'deactivate')
                {{-- Remarks --}}
                <div class="col-md-12 form-group">
                    @foreach ($RemarksList as $Remarks)
                            <label class="text-info">Deactivate Remarks | {{ $Remarks->created_at }}</label>
                            <p class="text-capitalize border-bottom pb-2">-> {{ $Remarks->remark_text }}</p>
                        @endforeach
                    </div>
                @endif
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

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('leadsStatus');
    const closeBox = document.getElementById('closeRemarkBox');
    const closeRemark = document.getElementById('closeRemark');
    const closeError = document.getElementById('closeRemarkError');

    // Show/hide box on status change
    statusSelect.addEventListener('change', function () {
        if (this.value.toLowerCase() === 'deactivate') {
            closeBox.style.display = 'block';
        } else {
            closeBox.style.display = 'none';
            closeRemark.value = '';
            closeError.classList.add('d-none');
        }
    });

    // Form validation
    const form = statusSelect.closest('form');
    form.addEventListener('submit', function (e) {
        if (statusSelect.value.toLowerCase() === 'deactivate' && closeRemark.value.trim() === '') {
            e.preventDefault();
            closeError.classList.remove('d-none');
            closeRemark.focus();
        }
    });

    // Trigger change event on page load to restore old value
    // statusSelect.dispatchEvent(new Event('change'));
    
});
  function handleLabourTypeChange() {
      var select = document.getElementById('LabourTypes');
      var selectedOption = select.options[select.selectedIndex];
      var LabourType = selectedOption.getAttribute('data-role');
      var login_access_section = document.getElementById('login_access_section');
      
      if (LabourType === 'Supervisor' || LabourType === 'Representative') {
          login_access_section.style.display = 'block';
        } else {
            login_access_section.style.display = 'none';
        }
    }
    
    function showpassword() {
        var passwordField = document.getElementById('password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }
</script>
@endpush
