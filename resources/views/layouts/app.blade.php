<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Steps</title>
    @include('../components/link')
</head>
@if(Route::is('login') || Route::is('reset'))
<body class="hold-transition login-page" style="background-image: url({{ asset('assets/images/login-bg.jpg') }}); ">
@else
<body class="hold-transition sidebar-mini layout-fixed">
@endif

    <div class="overlay" id="overlay"></div>

    <div class="loader-container" id="loaderContainer">
        <div class="circle-loader"></div>
    </div>
    {{-- @unless(Route::is('login') )
        
        @include('layouts.header')
    @endunless --}}
    @yield('content')
    {{-- @unless(Route::is('login') || Route::is('signup') || Route::is('forgotpassword'))
        @include('layouts.footer')
    @endunless --}}
</body>
</html>
