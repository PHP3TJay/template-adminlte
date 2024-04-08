@extends('layouts.app')

@section('content')
<div class="login-box">
    <div class="card login-div">
        <div class="card-header text-center">
            <img src="{{ asset('assets/images/coachinglogo.png') }}" width="300px">
        </div>
        <div class="card-body">
            <form method="POST" class="pt-3" id="passwordResetForm" method="post">
                @csrf
                <div class="input-group mb-3" style="justify-content: space-between; display: flex; ">
                    <label>Looks like this is your first time logging in. Please change your password first. </label>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="c_password" placeholder="Confirm Password" name="c_password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="button" id="changePasswordButton" class="btn btn-block btn-primary font-weight-medium auth-form-btn">Change Password</button>
                    </div>
                </div>
            </form>
            <p class="mb-1">
                {{-- <a href="forgot-password.html">I forgot my password</a> --}}
            </p>
        </div>
    </div>
</div>

@include('../components/script')
@include('custom_js.password_reset_js')
@endsection
