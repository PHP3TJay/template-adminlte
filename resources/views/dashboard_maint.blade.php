@extends('layouts.app')

@section('content')
@include('components.navbar')
@include('components.sidebar')
<div class="wrapper">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h1 class="headline text-warning"> 503 </h1>

        <div class="error-content col-lg-12">
          <h3><i class="fas fa-exclamation-triangle text-warning"></i> Service Unavailable</h3><br>
          <h4>Coming Soon</h4>
          <p>
            Welcome to Mysteps (Coaching Log v2) <br>
            Our service is currently under development and will be launching on  <br> <b><u>Monday March 18, 2024.</u></b>
            Thank you for your patience.
            
          </p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->
  </div>
</div>
@include('../components/script')
@endsection

