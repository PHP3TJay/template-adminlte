@extends('layouts.app')

@section('content')
@include('components.navbar')
@include('components.sidebar')
@include('custom_link.team_link')
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
                                <h4 class="card-title font-weight-bolder">Team Details</h4>
                                <a class="btn btn-sm btn-primary float-right ml-2" href="/team"><i class="fa fa-arrow-right"></i> Back </a>
                                <button class="btn btn-success btn-sm float-right" onclick="showTeamUserModal()"><i class="fa fa-plus"></i> Add User</button>
                            </div>
                            <div class="card-body" >
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="col-lg-12 border">
                                            <form class="row  p-2" enctype="multipart/form-data" id="edit-team-form">
                                                <label>Team Information</label>
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" class="form-control edit-team-input" name="team_id" value="{{$team->id}}" id="team_id">
                                                <div class="form-group col-lg-12">
                                                  <div class="form-group col-lg-12">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" placeholder="Name of the team" name="name" maxlength="50" value="{{$team->name}}">
                                                  </div>
                                                  <div class="form-group row col-lg-12">
                                                    <label class="col-sm-12 col-form-label">Status</label>
                                                    <div class="col-sm-4">
                                                      <div class="form-check">
                                                        <label class="form-check-label">
                                                          <input type="radio" class="form-check-input" name="status"  value="1" @if ($team->status == 1) checked @endif> Active <i class="input-helper"></i></label>
                                                      </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                      <div class="form-check">
                                                        <label class="form-check-label">
                                                          <input type="radio" class="form-check-input" name="status"  value="2" @if ($team->status == 2) checked @endif> Inactive <i class="input-helper"></i></label>
                                                      </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-check">
                                                          <label class="form-check-label">
                                                            <input type="radio" class="form-check-input" name="status" value="0" @if ($team->status == 0) checked @endif> Disable <i class="input-helper"></i></label>
                                                        </div>
                                                      </div>
                                                  </div>
                                                  <div class="form-group col-lg-12">
                                                    <label for="name">Description</label>
                                                    <textarea class="form-control" name="description" rows="4" maxlength="200" id="description">{{$team->description}}</textarea>
                                                  </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <button id="captureDataBtn" class="btn btn-info btn-sm float-right" onclick=updateTeam(event)><i class="fa fa-save"></i>&nbsp; Update Team</button>
                                                </div>
                                            </form>
                                        </div>
                                        
                                        <div class=" sortable-container border">
                                            <label class="mt-2">Positions</label>
                                            <div class="row">
                                                <div class="form-group col-lg-12">
                                                    <button type="button" class="btn btn-primary btn-sm float-right" id="addPositionBtn"><i class="fa fa-plus"></i> Add Position</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                              <div class="column col-lg-12">
                                                <ul class="connected-sortable droppable-area col-lg-12" id="sortablePositions">
                                                  @if ($team_positions->isNotEmpty())
                                                    @foreach ($team_positions as $team_position)
                                                    <li class="draggable-item border">
                                                      <div class="row">
                                                        <div class="col-lg-11">
                                                          <span><i class="fa fa-bars"></i> &nbsp;&nbsp;&nbsp;{{$team_position->title}}</span>
                                                          <input type="hidden" class="form-control" name="position_title[]" value="{{$team_position->title}}">
                                                          <input type="hidden" class="form-control" value="{{$team_position->id}}" name="position_id[]">
                                                        </div>
                                                        <div class="col-lg-1">
                                                          <input type="checkbox" class="form-control-sm" @if($team_position->is_active) checked @endif>
                                                        </div>
                                                      </div>
                                                    </li>
                                                    @endforeach
                                                  @endif
                                                  
                                                </ul>
                                              </div>
                                              
                                                <div class="form-group mt-3 col-lg-12">
                                                    <button id="captureDataBtn" class="btn btn-success btn-sm float-right" onclick=updatePosition()><i class="fa fa-save"></i>&nbsp; Update Positions</button>
                                                </div>
                                            </div>
                                        </div>
                                          
                                    </div>
                                    <div class="col-lg-8 px-4">
                                        <table id="team-user-table" class="table table-hover table-striped" >
                                            <thead>
                                                <tr>
                                                    <th>Employee ID</th>
                                                    <th>Name</th>
                                                    <th>Position</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>



@include('modals.add_team_user')
@include('modals.view_team')
@include('modals.delete_team')
@include('../components/script')
@include('custom_js.team_js')
@include('custom_js.team_edit_js')
@endsection