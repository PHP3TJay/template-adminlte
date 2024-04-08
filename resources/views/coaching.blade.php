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
                                <h4 class="card-title font-weight-bolder">
                                    @if(Route::is('coaching-log')) 
                                        Coaching Creation
                                    @elseif (Route::is('coaching-follow-through')) 
                                        Follow Through
                                    @elseif (Route::is('coaching-canceled')) 
                                        Canceled Coaching
                                    @elseif (Route::is('coaching-declined')) 
                                        Declined Coaching
                                    @elseif (Route::is('coaching-accepted')) 
                                        Accepted Coaching
                                    @elseif (Route::is('coaching-due')) 
                                        Due Coaching
                                    @elseif (Route::is('coaching-completed')) 
                                        Completed Coaching Creation
                                    @endif
                                </h4>
                                @if(Route::is('coaching-log')) 
                                    <button class="btn btn-success btn-sm float-right" onclick="showCoachingModal()"><i class="fa fa-plus"></i> Add New Coaching Log</button>
                                @endif
                            </div>
                            <div class="card-body">
                                <table id="coaching-table" class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Week</th>
                                            <th>Agent</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Date Coached</th>
                                            @if (!Route::is('coaching-canceled') && !Route::is('coaching-due') && !Route::is('coaching-accepted'))
                                                <th>Next Date Coached</th>
                                            @endif
                                            @if(Route::is('coaching-due') || Route::is('coaching-accepted')) 
                                                <th>Follow Through</th>
                                            @endif
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
@include('modals.add_log')
@include('modals.view_log')
@include('components.script')
@include('custom_js.coaching_log_js')
@endsection