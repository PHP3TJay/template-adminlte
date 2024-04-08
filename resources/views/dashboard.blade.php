@extends('layouts.app')

@section('content')
@include('components.navbar')
@include('components.sidebar')
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    @if(!$lowest)
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{$coachingLogsCount}}</h3>
                                    <p>Coaching Creation</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-handshake  "></i>
                                </div>
                                <a href="/coaching-log" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{$accepted}}</h3>
                                    <p>Accepted Coaching</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-handshake  "></i>
                                </div>
                                <a href="/coaching-accepted" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{$coachingCanceled}}</h3>
                                    <p>Canceled Coaching</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-handshake  "></i>
                                </div>
                                <a href="/coaching-canceled" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{$coachingDeclined}}</h3>
                                    <p>Declined Coaching</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-handshake  "></i>
                                </div>
                                <a href="/coaching-declined" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{$coachingLogsCompletedCount}}</h3>
                                    <p>(Completed) Coaching Creation</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-handshake  "></i>
                                </div>
                                <a href="/coaching-completed" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{$coachingFollowThrough}}</h3>
                                    <p>Follow Through</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-handshake  "></i>
                                </div>
                                <a href="/coaching-follow-through" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{$due}}</h3>
                                    <p>(Due)Accepted Coaching</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-handshake  "></i>
                                </div>
                                <a href="/coaching-due" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        
                        {{-- <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{}}</h3>
                                    <p>(Due) Accepted Coaching</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-handshake  "></i>
                                </div>
                                <a href="/coaching-completed-follow-through" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div> --}}
                    @endif
                    @if(!in_array($currentRoleUser->role_id, [1, 2]))
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{$mycoaching}}</h3>
                                <p>My Coaching</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-handshake  "></i>
                            </div>
                                <a href="/my-coaching" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif
                    @if(in_array($currentRoleUser->role_id, [1, 2]))
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{$teams}}</h3>
                                <p>Users</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users  "></i>
                            </div>
                                <a href="{{ config('app.url') }}/user" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{$users}}</h3>
                                <p>Teams</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-address-book  "></i>
                            </div>
                                <a href="{{ config('app.url') }}/team" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @if($latestRole->id != $currentRoleUser->role_id)
                    {{-- <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Monthly Coaching</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="position-relative mb-4">
                                    <canvas id="monthly-count" height="250px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Category Count</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="position-relative mb-4">
                                    <canvas id="category-count" height="250px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@include('../components/script')
@include('custom_js.dashboard_js')
@endsection