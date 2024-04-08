<div class="row">
    <div class="col-lg-6 border">
        <div class="row">
            <div class="col-12">
                <div class=" form-group mx-auto"> <b>User Details</b></div>
            </div>
            <div class="form-group col-lg-12 mt-2">
                <div class="row">
                    <div class="form-group col-lg-4">
                        
                        <button class="btn btn-sm btn-outline-success col-12" onclick="editUserDetails()" id="editButton"><i class="fa fa-edit"></i> Edit User</button> 
                    </div>
                    <div class="form-group col-lg-4">
                        <button class="btn btn-sm btn-outline-info col-12"  onclick="resetPasswordBtn({{$user->id}},'{{$user->firstname}}')" ><i class="fa fa-circle-notch"></i> Reset Password</button>
                    </div>
                    <div class="form-group col-lg-4">
                        <button class="btn btn-sm btn-outline-primary col-12" onclick="unlockAccountBtn('{{$user->username}}','{{$user->firstname}}')"><i class="fa fa-unlock"></i> Unlock Account</button>
                    </div>
                </div>
            </div>
            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
            <div class="form-group col-6">
                <label>First Name</label>
                <input type="text" class="form-control form-control-sm" disabled name="firstname" id="firstname" value="{{ $user->firstname}}" />
            </div>
            <div class="form-group col-6">
                <label>Last Name</label>
                <input type="text" class="form-control form-control-sm" disabled name="lastname" id="lastname" value="{{ $user->lastname}}" />
            </div>
        </div>
        <div class="row">
            <div class="form-group col-6">
                <label>Middle Name</label>
                <input type="text" class="form-control form-control-sm" disabled name="middlename" id="middlename" value="{{ $user->middlename}}" />
            </div>
            <div class="form-group col-6">
                <label>Email</label>
                <input type="email" class="form-control form-control-sm" disabled name="email" id="email" value="{{ $user->email}}" />
            </div>
        </div>
        <div class="row">
            <div class="form-group col-6">
                <label>Employee ID</label>
                <input type="text" class="form-control form-control-sm" disabled name="employee_id" id="employee_id" value="{{ $user->employee_id}}" />
            </div>
            <div class="form-group col-6">
                <label>Status</label>
                <select class="form-control form-control-sm select2 " disabled name="account_status" id="account_status" >
                    <option value="active" @if ($user->account_status == 'active') selected @endif>Active</option>
                    <option value="deactivate" @if ($user->account_status == 'deactivate') selected @endif>Deactive</option>
                    <option value="inactive" @if ($user->account_status == 'inactive') selected @endif>Inactive</option>
                </select>
            </div>
        </div>
        {{-- @if ($user->role->isNotEmpty())
            @foreach($user->roles as $roleData)
            <div class="row">
                <div class="form-group col-6 role-team-div">
                    <label>Team</label>
                    <select class="form-control select2 team-select form-control-sm" style="width: 100%;" name="team_id[]" disabled>
                        <option value=""> Select Team </option>
                        @if ($teams->isNotEmpty())
                            @foreach ($teams as $team)
                                <option value="{{$team->id}}" @if ($roleData->team->id == $team->id) selected @endif > {{$team->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group col-6 role-team-div">
                    <label>Position</label>
                    <select class="form-control select2 team-select form-control-sm" style="width: 100%;" name="team_id[]" disabled>
                        <option value=""> Select Team </option>
                        @if ($teams->isNotEmpty())
                            @foreach ($teams as $team)
                                <option value="{{$team->id}}" @if ($roleData->team->id == $team->id) selected @endif > {{$team->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            @endforeach
        @endif --}}
    </div>
    {{-- <div class="col-lg-2 border">
        <div class="col-12">
            <div class=" form-group mx-auto"> <b>Permission</b></div>
            <div class="form-group" id="permissionSection">
                <table class="table table-striped">
                    <thead>
                        <th><b>Module Name</b></th>
                        <th><b>Permission</b></th>
                    </thead>
                    <tbody>
                        @if ($modules->isNotEmpty())
                            @foreach ($modules as $module)
                                <tr>
                                    <td>{{ $module->name }}</td>
                                    @php
                                        $hasPermission = false;
                                    @endphp
                                    @if ($permissions->isNotEmpty())
                                        @foreach ($permissions as $permission)
                                            @if ($permission->module_id == $module->id)
                                                @php
                                                    $hasPermission = true;
                                                @endphp
                                                <td><input type="checkbox" data-module-id="{{ $module->id }}" class="form-check-input" checked disabled></td>
                                                @break
                                            @endif
                                        @endforeach
                                    @endif

                                    @if (!$hasPermission)
                                        <td><input type="checkbox" data-module-id="{{ $module->id }}" class="form-check-input" disabled></td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
    <div class="col-lg-6 border">
        <div class="col-12">
            <div class=" form-group mx-auto"> <b>Log History</b></div>
        </div>
        <div class="table-responsive">
            <table id="order-listing" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Host Name</th>
                        <th>Mac Address</th>
                        <th>Date Logged In</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($user->loginHistory->isNotEmpty())
                        @foreach($user->loginHistory as $loginHistory)
                            <tr>
                                <td>{{ $loginHistory->hostname }}</td>
                                <td>{{ substr($loginHistory->mac_address, 0, 17) }}</td>
                                <td>{{ $loginHistory->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">No Data</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $('#viewModal select:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent()
        });
    });
</script>