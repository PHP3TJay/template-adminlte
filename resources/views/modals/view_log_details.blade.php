<div class="col-lg-12 row">
    <div class="form-group col-lg-3">
        <label>Agent Name</label>
        <input class="form-control form-control-sm" type="text" value="{{$agent_name}}" disabled >
        <input type="hidden" value="{{$coachingLogDetail->agent_id}}" name="agent_id">
        <input type="hidden" value="{{$coachingLogDetail->id}}" name="coaching_log_detail_id">
    </div>
    <div class="form-group col-lg-3">
        <label class="fw-bold">Category</label>
        <select class="form-control form-control-sm" name="category_id" id="category_id">
            <option value="">Select Category</option>
            @if ($categories->isNotEmpty())
                @foreach ($categories as $category)
                    <option value="{{$category->id}}" @if ($category->id == $coachingLogDetail->category_id) selected @endif>{{$category->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group @if ($coachingLog->coach_id == auth()->user()->id) col-lg-2 @else col-lg-3 @endif">
        <label class="fw-bold">Channel</label>
        <select class="form-control-sm" name="channel_select" id="channel_select">
            <option value="" >Select Channel</option>
            <option value="Face to Face" @if($coachingLogDetail->channel == "Face to Face") selected @endif >Face to Face</option>
            <option value="Online Meeting" @if($coachingLogDetail->channel == "Online Meeting") selected @endif >Online Meeting</option>
        </select>
    </div>
    <div class="form-group @if ($coachingLog->coach_id == auth()->user()->id) col-lg-2 @else col-lg-3 @endif ">
        <label class=" fw-bold">Coaching Date</label>
        <input type="date" class="form-control form-control-sm" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="mm/dd/yyyy" name="date_coached" value="{{$coachingLogDetail->date_coached}}" id="date_coached" min="{{ date('Y-m-d') }}">
    </div>
    <div class="form-group col-lg-2 " @if ($coachingLog->coach_id != auth()->user()->id) style="display:none" @endif>
        <label class=" fw-bold"><input type="checkbox" style="transform: scale(1.5);" id="checkbox_next_date">&nbsp;&nbsp; Next Coaching Date </label>
        <input type="date" class="form-control form-control-sm" data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="mm/dd/yyyy" name="next_date_coached"  id="next_date_coached" min="{{ date('Y-m-d') }}" disabled>
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group ">
        <label for="name">Goal</label> 
        <textarea name="goal" class="summernote form-control-sm" id="goal">
            {{$coachingLogDetail->goal}}
          </textarea>
    </div>
    <div class="form-group">
        <label for="name">Option</label>
        <textarea name="option" class="summernote form-control-sm" id="option">
            {{$coachingLogDetail->option}}
          </textarea>
    </div>
</div>
<div class="col-lg-6">
    <div class="form-group ">
        <label for="name">Reality</label>
        <textarea name="reality" class="summernote form-control-sm" id="reality">
            {{$coachingLogDetail->reality}}
          </textarea>
    </div>
    <div class="form-group">
        @if(!empty($coachingLogDetail->reason))
            <label class="text-danger">Coaching Declined</label>
            <label>Reason : <span>{{$coachingLogDetail->reason}}</span></label>
        @else
            <label for="name">Will</label>
            <textarea name="will" class="summernote will form-control-sm" id="will" >
                {{$coachingLogDetail->will}}
            </textarea>
        @endif
    </div>
</div>
<div class="col-lg-12 row d-flex justify-content-center mt-4">
    @if ($coachingLog->coach_id == auth()->user()->id)
        @if($coachingLogDetail->status == 0 )
            <button class="btn btn-success col-lg-2" onclick="saveChangesBtn()"><i class="fa fa-save"></i> Save Changes</button>&nbsp;
            
        @elseif ($coachingLogDetail->status == 1 )
            <button class="btn btn-success col-lg-2" id="completeCoachingBtn" onclick="completeCoachingBtn()"><i class="fa fa-save"></i> Complete Coaching</button>&nbsp;
            
        @endif

        @if($coachingLogDetail->status == 0 || $coachingLogDetail->status == 1 )
            <button class="btn btn-danger col-lg-2" onclick="cancelCoachingBtn('{{$coachingLogDetail->id}}')"><i class="fa fa-trash"></i> Cancel Coaching</button>
        @endif
    @else
        @if($coachingLogDetail->status == 0 )
            <button class="btn btn-primary col-lg-2" onclick="acceptCoaching()"><i class="fa fa-check"></i> Accept Coaching</button>&nbsp;
            <button class="btn btn-danger col-lg-2" onclick="declineCoachingBtn('{{$coachingLogDetail->id}}')"><i class="fa fa-exclamation" ></i> Decline Coaching</button>&nbsp;
        @endif
    @endif
    
    
</div>
<script>
$(document).ready(function() {
    $('.summernote').summernote({
        height: 150,
        toolbar: [
            ["style", ["style"]],
            ["font", ["bold", "underline", "clear"]],
            ["fontname", ["fontname"]],
            ["color", ["color"]],
            ["table", ["table"]],
        ]
    });

    @if ($coachingLog->coach_id == auth()->user()->id)
        $('.will').summernote('disable');
    @endif

    @if ($coachingLogDetail->agent_id == auth()->user()->id)
        $('#goal').summernote('disable');
        $('#reality').summernote('disable');
        $('#option').summernote('disable');
        $('#category_id').prop('disabled', true);
        $('#date_coached').prop('disabled', true);
        $('#channel_select').prop('disabled', true);
        $('#checkbox_next_date').hide();




    @endif

    $('#view-coaching select:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent()
        });
    });

    @if($coachingLogDetail->status == 2)
        if ($('#printButton').length) {
            $('.modal-header .print-div #printButton').remove();
        }
            var printButton = $('<a>', {
                class: 'btn btn-default btn-sm px-4',
                id: 'printButton',
                href: '/coaching/print/' + {{$coachingLogDetail->id}} ,
                target: 'blank',
                rel: 'noopener'
            }).append('<i class="fa fa-print"></i>&nbsp;Print');
            $('.modal-header .print-div').append(printButton);
    @else
        $('.modal-header .print-div #printButton').remove();
        
    @endif


    $('#checkbox_next_date').change(function() {
      var isChecked = $(this).prop('checked');
      $('#next_date_coached').prop('disabled', !isChecked);

      var buttonText = isChecked ? 'Follow Through' : 'Complete Coaching';
      var buttonClass = isChecked ? 'btn-warning' : 'btn-success';

      // Animate the button change
      $('#completeCoachingBtn')
        .fadeOut(200, function() {
          $(this).text(buttonText).removeClass('btn-success btn-warning').addClass(buttonClass);
        })
        .fadeIn(200);
    });

    
});
</script>
