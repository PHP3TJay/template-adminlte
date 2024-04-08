<div class="modal fade" id="add-team" tabindex="-1" role="dialog" aria-labelledby="add-team" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" enctype="multipart/form-data" id="add-team-form">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel-2">Add Team</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="clodeModal('add-team')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Country Name Ex. Philippines" name="name">
                </div>
                <div class="form-group">
                    <label for="name">Description</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="teamLogo">Team Logo</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="teamLogo" name="img">
                            <label class="custom-file-label" for="teamLogo">Choose file</label>
                        </div>
                    </div>
                    <input type="hidden" class="form-control file-upload-info" disabled placeholder="Upload Image" name="logo">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="submitTeamForm()">Submit</button>
                <button type="button" class="btn btn-light"  onclick="clodeModal('add-team')">Cancel</button>
            </div>
        </form>
    </div>
</div>