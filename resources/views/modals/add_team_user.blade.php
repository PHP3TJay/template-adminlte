<div class="modal fade" id="add-team-user" tabindex="-1" role="dialog" aria-labelledby="add-team" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" enctype="multipart/form-data" id="add-team-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-2">Add Users</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="clodeModal('add-user')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="fw-bold">Position</label>
                    <select class="form-control " name="position_id" id="position_id">
                        <option value="">Select Position</option>
                        @if ($team_positions->isNotEmpty())
                            @foreach ($team_positions as $position)
                                <option value="{{$position->id}}" >{{$position->title}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Users To Add</label>
                    <select class="select2" multiple="multiple" data-placeholder="Select Users" style="width: 100%;" data-dropdown-css-class="select2-blue" name="user_id[]">
                    </select>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="submitTeamUser()">Submit</button>
                <button type="button" class="btn btn-light"  onclick="clodeModal('add-user')">Cancel</button>
            </div>
        </form>
    </div>
</div>