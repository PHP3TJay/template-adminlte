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
        <h2 class="headline text-warning"> 503 </h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-warning"></i> Service Unavailable</h3>

          <p>
            Welcome to Mysteps (Coaching Log v2) <br>
            Service is temporarily unavailable. Service will start on Monday, March 18, 2024.
            
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

