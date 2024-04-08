@include('custom_link.team_link')
<div class="row">
  <form class="row col-lg-6" enctype="multipart/form-data" id="edit-team-form">
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
      
      
      <div class="form-group row col-lg-12">
        <div class="form-group col-lg-6">
          <label for="teamLogo" class="col-lg-12">Team Logo</label>
          <div class="input-group">
              <div class="custom-file">
                  <input type="file" class="custom-file-input" id="teamLogo" name="img">
                  <label class="custom-file-label" for="teamLogo">Choose file</label>
              </div>
          </div>
          <input type="hidden" class="form-control file-upload-info" disabled placeholder="Upload Image" name="logo">
        </div>
        <div class="col-lg-6">
          <center><img src="{{asset('storage/'.$team->logo)}}" height="50%" width="50%" style="border"></center>
        </div>
      </div>
    </div>
    <button id="captureDataBtn" class="btn btn-info" onclick=updateTeam(event)><i class="fa fa-save"></i>&nbsp; Update Team</button>
  </form>
  <div class="col-lg-6">
    <div class=" sortable-container col-lg-12">
      <h5>Positions</h5>
      <div class="row">
        <div class="form-group col-lg-8">
            <button type="button" class="btn btn-primary" id="addPositionBtn"><i class="fa fa-plus"></i> Add Position</button>
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
      </div>
    </div>
    <div class="form-group mt-3">
      <button id="captureDataBtn" class="btn btn-success" onclick=updatePosition()><i class="fa fa-save"></i>&nbsp; Update Positions</button>
    </div>
  </div>
</div>


<script>
$(function () {
  bsCustomFileInput.init();

  $("#addPositionBtn").on("click", function() {
        var newPosition = '<li class="draggable-item border">' +
                            '<div class="row">' +
                                
                                '<div class="col-lg-5">' +
                                    '<span><i class="fa fa-bars"></i> &nbsp;&nbsp;&nbsp;<span class="position-title">(New Position)</span></span>' +
                                '</div>' +
                                '<div class="col-lg-6">' +
                                    '<label for="positionTitle">Position Title:</label>' +
                                    '<input type="text" class="form-control" name="position_title[]">' +
                                    '<input type="hidden" class="form-control" value="new" name="position_id[]">' +
                                '</div>' +
                                '<div class="col-lg-1">' +
                                    '<input type="checkbox" class="form-control-sm" checked>' +
                                '</div>' +
                            '</div>' +
                        '</li>';
        $("#sortablePositions").append(newPosition);
    });
});

$( init );

function init() {
  $( ".droppable-area" ).sortable({
    connectWith: ".connected-sortable",
    stack: '.connected-sortable ul'
  }).disableSelection();
}
</script>