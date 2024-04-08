<div class="modal fade" id="delete-team">
    <div class="modal-dialog modal-md">
        <form id="delete-team-form" action="{{ route('team.delete_team', ['id' => '__TEAM_ID__']) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-body">
                    <label>Are you sure you want to delete this team?</label>
                    <input type="hidden" class="form-control delete-team-input" name="team_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>