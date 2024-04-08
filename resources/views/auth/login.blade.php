@extends('layouts.app')

@section('content')
<div class="login-box">
    <div class="card login-div">
        <div class="card-header text-center">
            <img src="{{ asset('assets/images/coachinglogo.png') }}" width="300px">
        </div>
        <div class="card-body">
            <form {{ route('login') }}" method="POST" class="pt-3" id="loginForm" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="username" placeholder="Username" name="username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="button" id="loginButton" class="btn btn-block btn-primary font-weight-medium auth-form-btn">SIGN IN</button>
                    </div>
                </div>
            </form>
            <p class="mb-1 mt-3">
                <a href="{{ config('app.url') }}/account-helper">Having Problem Signing In? Click Here. <i class="far fa-hand-point-left fa-2x"></i></a>
            </p>
        </div>
    </div>
</div>

@include('../components/script')
@include('custom_js.login_js')
@endsection
