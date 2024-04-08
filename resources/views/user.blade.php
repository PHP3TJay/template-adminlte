@extends('layouts.app')

@section('content')
@include('components.navbar')
@include('components.sidebar')
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title font-weight-bolder">User</h4>
                                <button class="btn btn-success btn-sm float-right" onclick="showUserModal()"><i class="fa fa-plus"></i> Add New User</button>
                            </div>
                            <div class="card-body" >
                                <table id="user-table" class="table table-hover table-striped" >
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Employee ID</th>
                                            <th>Username</th>
                                            <th>Email Address</th>
                                            <th>Status</th>
                                            <th>Last Login Date</th>
                                            <th>Hostname</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody ></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@include('components.script')
@include('custom_js.user_js')
@include('modals.add_user')
@include('modals.view_user')

@endsection
