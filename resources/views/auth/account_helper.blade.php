@extends('layouts.app')
@section('content')
<style>
    .transition-form {
        transition: opacity 0.5s ease-in-out;  
    }
</style>
    <div class="login-box">
        <div class="card login-div">
            <div class="card-header text-center">
                <img src="{{ asset('assets/images/coachinglogo.png') }}" width="300px">
            </div>
            <div class="card-body">
                <div class="form-container transition-form" id="helpFormContainer">
                    <form method="POST" class="pt-3" method="post">
                        @csrf
                        <div class="input-group mb-1" style="justify-content: space-between; display: flex; ">
                            <label>How may we help you?</label>
                        </div>
                        <a href="#" class="link-item" onclick="toggleForm('forgotPasswordForm', 'helpFormContainer')"><i class="far fa-hand-point-right fa-1x"></i>&nbsp; Forgot Password </a><br>
                        <a href="#" class="link-item" onclick="toggleForm('forgotUsernameForm', 'helpFormContainer')"><i class="far fa-hand-point-right fa-1x"></i>&nbsp; Forgot Username </a><br>
                        <a href="{{ config('app.url') }}/" class="link-item"><i class="far fa-hand-point-right fa-1x"></i>&nbsp; Back To Login Page</a><br>
                    </form>
                </div>
                <div class="form-container transition-form" id="forgotPasswordForm" style="display:none;">
                    <a href="#" class="link-item float-right" onclick="toggleForm('helpFormContainer', 'forgotPasswordForm')">Back <i class="far fa-hand-point-left fa-1x"></i></a><br>
                    <form method="POST" class="pt-3 flip-form" id="passwordResetForm" method="post">
                        <label style="justify-content: space-evenly">Please input your username and email so that we could send a password reset.</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="username" placeholder="Username" name="username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="email" placeholder="Email Address" name="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" id="forgotPasswordBtn" onclick="forgotPasswordRequest()" class="btn btn-block btn-primary font-weight-medium auth-form-btn">SUBMIT</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="form-container transition-form" id="forgotUsernameForm" style="display:none;">
                    <a href="#" class="link-item float-right" onclick="toggleForm('helpFormContainer', 'forgotUsernameForm')">Back <i class="far fa-hand-point-left fa-1x"></i></a><br>
                    <form method="POST" class="pt-3 flip-form" id="passwordResetForm" method="post">
                        <label>Please input your email so that we could send your username.</label>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" id="employee_id" placeholder="Employee ID" name="employee_id">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" id="email2" placeholder="Email Address" name="email2">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" id="forgotUsernameBtn" onclick="forgotUsernameRequest()" class="btn btn-block btn-primary font-weight-medium auth-form-btn">SUBMIT</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('../components/script')
    @include('custom_js.password_reset_js')
@endsection
