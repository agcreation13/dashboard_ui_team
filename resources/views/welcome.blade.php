@extends('layouts.app')
@section('main_content')
<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            {{-- <div class="col-md-6 col-lg-7">
                <img class="img-fluid " src="{{ url('/assets/theme/src/images/logo/main-logo.png') }}" alt="">
                <img src="{{ url('assets/theme/vendors/images/login-page-img.png') }}" alt="">
            </div> --}}
            <div class="col-md-12 col-lg-9">
                <div class="login-box bg-white box-shadow border-radius-10">
                    <div class="login-title">
                        <h2 class="text-center text-primary">Login To Application</h2>
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        @method('POST')
                        <div class="input-group custom">
                            <input type="email" class="form-control form-control-lg" name="email" value="{{ old('email') }}" required placeholder="Username/email">
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                            </div>
                       
                        </div>
                     <div class="input-group custom">
    <input type="password" id="password" class="form-control form-control-lg" name="password" required placeholder="**********">
    <div class="input-group-append custom" onclick="togglePassword()">
        <span class="input-group-text"><i id="toggleIcon" class="fa fa-eye-slash"></i></span>
    </div>
 
</div>
                
                        <div class="row">
                            <div class="col-sm-12">
                                      <div class="p-2">
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                      </div>
                                <div class="input-group mb-0">
                                    <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
                                    {{-- <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In"> --}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    }
</script>
@endsection
