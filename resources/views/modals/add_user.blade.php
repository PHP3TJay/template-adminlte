<div class="modal fade" id="add-user" tabindex="-1" role="dialog"  aria-hidden="true" onclick="showUserModal()">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="POST" enctype="multipart/form-data" id="add-user-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-2">Add User</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Employee ID</label>
                            <input type="text" class="form-control"  placeholder="9950205" name="employee_id">
                        </div>
                        <div class="form-group">
                            <label for="name">First Name</label>
                            <input type="text" class="form-control"  placeholder="Juan" name="firstname">
                        </div>
                        <div class="form-group">
                            <label for="name">Middle Name</label>
                            <input type="text" class="form-control"  placeholder="" name="middlename">
                        </div>
                        <div class="form-group">
                            <label for="name">Last Name</label>
                            <input type="text" class="form-control"  placeholder="Dela Cruz" name="lastname">
                        </div>
                        <div class="form-group">
                            <label for="name">Email Address</label>
                            <input type="email" class="form-control" placeholder="sample@sanmiguel.com" name="email">
                        </div>
                        <div id="team-role-container">
                            <div class="row">
                                <div class="form-group col-6">
                                    <label>Team</label>
                                    <select class="form-control form-control-sm p-3 select2" style="width:100%" name="team_id" id="team_select">
                                        <option value=""> Select Team </option>
                                        @if ($teams->isNotEmpty())
                                            @foreach ($teams as $team)
                                                <option value="{{$team->id}}"> {{$team->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label>Position</label>
                                    <select class="form-control form-control-sm p-3 select2" style="width:100%" name="team_position_id" id="position_select">
                                        <option value=""> Select Position </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 pr-4">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>(MyPat) Region</label>
                            <select class="form-control form-control-sm select2" style="width:100%" name="region" id="region_select">
                                <option value=""> Select Region </option>
                                @if ($region->isNotEmpty())
                                    @foreach ($region as $region_data)
                                        <option value="{{$region_data->region_name}}"> {{$region_data->region_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>(MyPat) Site Address</label>
                            <select class="form-control form-control-sm select2" style="width:100%" name="site_address" id="site_address_select">
                                <option value=""> Select Site Address </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">(MyPat) Joined Date</label>
                            <input type="date" class="form-control" name="joined_date">
                        </div>
                        <div class="form-group">
                            <label>(MyPat) Team Name</label>
                            <select class="form-control form-control-sm select2" style="width:100%" name="team_name" id="team_name">
                                <option value=""> Select Team </option>
                                @if ($team_name->isNotEmpty())
                                    @foreach ($team_name as $team_name_data)
                                        <option value="{{$team_name_data->teamName}}"> {{$team_name_data->teamName}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>(MyPat) Team Leader</label>
                            <select class="form-control form-control-sm select2" style="width:100%" name="team_leader" id="team_leader">
                                <option value=""> Select Team </option>
                                @if ($team_leader->isNotEmpty())
                                    @foreach ($team_leader as $team_leader_data)
                                        <option value="{{$team_leader_data->team_leader}}"> {{$team_leader_data->team_leader}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>(MyPat) User Level</label>
                            <select class="form-control form-control-sm select2" style="width:100%" name="user_level" id="user_level">
                                <option value=""> Select Team </option>
                                @if ($user_level->isNotEmpty())
                                    @foreach ($user_level as $user_level_data)
                                        <option value="{{$user_level_data->id}}"> {{$user_level_data->description}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

