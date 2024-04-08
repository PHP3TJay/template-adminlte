<div class="modal fade" id="edit-team">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Team</h4>
            </div>
            <form id="edit-team-form" action="{{ route('team.edit_team', ['id' => '__TEAM_ID__']) }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" class="form-control edit-team-input" name="team_id" >
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="defaultconfig" placeholder="Name of the team" name="name" maxlength="50" >
                    </div>
                    <div class="form-group">
                        <label for="name">Description</label>
                        <textarea class="form-control" name="description" rows="3" maxlength="200" id="maxlength-textarea"></textarea>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-4">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="status" id="active" value="1" > Active <i class="input-helper"></i></label>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <div class="form-check">
                            <label class="form-check-label">
                              <input type="radio" class="form-check-input" name="status" id="inactive" value="0"> Inactive <i class="input-helper"></i></label>
                          </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Team Logo</label>
                        <input type="file" name="img" class="file-upload-default" id="edit-team-image">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image" name="logo">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>