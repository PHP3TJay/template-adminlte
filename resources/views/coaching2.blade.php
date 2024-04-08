@extends('layouts.app')

@section('content')
@include('components.navbar')
@include('components.sidebar')
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title font-weight-bolder">My Coaching</h4>
                            </div>
                            <div class="card-body">
                                <table id="coaching-table" class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Week</th>
                                            <th>Coach Name</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Date Coached</th>
                                            <th>Next Date Coached</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@include('modals.view_log')
@include('components.script')
@include('custom_js.coaching_log_js2')
@endsection