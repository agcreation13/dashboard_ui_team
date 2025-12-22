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
                    <li class="breadcrumb-item active text-primary">Show</li>
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
        <form>
            <div class="row">
                {{-- Labour id --}}
                   <div class="col-md-2 form-group">
                    <label>Labour-ID</label>
                    <input class="form-control" readonly  type="text" name="labour_id" value="{{ old('labour_id',$labourDetail->labour_id) }}" placeholder="Labour Name" >
                    @error('labour_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- Labour Name --}}
                <div class="col-md-4 form-group">
                    <label>Labour Name <sup class="text-danger">*</sup></label>
                    <input class="form-control" readonly   type="text" name="name" value="{{$labourDetail->name}}" placeholder="Labour Name" >
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Phone No --}}
                <div class="col-md-3 form-group">
                    <label>Phone No <sup class="text-danger">*</sup></label>
                    <input class="form-control" readonly  type="text" name="phoneno" value="{{$labourDetail->phoneno}}" placeholder="Phone No" >
                    @error('phoneno') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                {{-- Aadhar No --}}
               <div class="col-md-3 form-group">
                    <label>Aadhar No <sup class="text-danger">*</sup></label>
                    <input class="form-control" readonly  type="text" name="aadhar_no" value="{{ old('aadhar_no',$labourDetail->aadhar_no) }}" placeholder="Aadhar No"
                        pattern="\d{12}" maxlength="12" minlength="12" title="Aadhar number must be exactly 12 digits" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,12);">
                    @error('aadhar_no') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Labour Type --}}
                <div class="col-md-6 form-group">
                    <label>Labour Type <sup class="text-danger">*</sup></label>
                    <select readonly class="form-control" name="role" >
                    <option value="{{ $labourDetail->role }}">{{ $labourDetail->labourRole->name }}</option>
                    </select>
                    @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Daily Wage --}}
                <div class="col-md-3 form-group">
                    <label>Daily Wage (₹) <sup class="text-danger">*</sup></label>
                    <input class="form-control" readonly  type="number" name="dailywage" value="{{$labourDetail->dailywage}}" placeholder="₹" >
                    @error('dailywage') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Status --}}
                <div class="col-md-3 form-group">
                    <label>Status <sup class="text-danger">*</sup></label>
                    <select readonly class="form-control text-capitalize" name="status" id="leadsStatus" >
                     
                             <option value="{{ $labourDetail->status }}"  >{{ $labourDetail->status }}</option>
                            </option>
                     
                    </select>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
           {{-- Close Remark Box --}}
              @if($labourDetail->status === 'deactivate')
                        {{-- Remarks --}}
                        <div class="col-md-12 form-group">
                            @foreach ($RemarksList as $Remarks)
                            <label class="text-info">Deactivate Remarks | {{$Remarks->created_at}}</label>
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
    statusSelect.dispatchEvent(new Event('change'));
});
</script>

@endpush