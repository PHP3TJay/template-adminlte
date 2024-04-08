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
                                <h4 class="card-title font-weight-bolder">Team</h4>
                                <button class="btn btn-success btn-sm float-right" data-bs-toggle="modal" data-bs-target="#add-team" onclick="showTeamModal()"><i class="fa fa-plus"></i> Add New Team</button>
                            </div>
                            <div class="card-body" >
                                <table id="team-table" class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>



@include('modals.add_team')
@include('modals.view_team')
@include('modals.delete_team')
@include('../components/script')
@include('custom_js.team_js')
@endsection