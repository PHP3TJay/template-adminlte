<script>
$(document).ready(function() {

    $('.summernote').summernote({
        height: 150,
        //width: 850,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol']],
            ["table", ["table"]],
        ]
    });
    
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    var datatableUrl = "/getCoachingData" + window.location.pathname.replace('/coaching-', '/');
    console.log(datatableUrl);

    $('#coaching-table').DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": datatableUrl,
            "type": "GET",
            "headers": {
                "X-CSRF-TOKEN": csrfToken
            }
        },
        "columns": [
            { "data": "id", "width": "1%" , "orderable": false },
            { "data": "week", "width": "5%", "orderable": false  }, 
            { "data": "agent", "width": "13%", "orderable": false  },
            { "data": "category", "width": "15%", "orderable": false  },
            { 
                "data": "status", 
                "width": "5%",
                "orderable": false,
                "render": function (data, type, row) {
                    if (data == 0) {
                        return '<label class="badge badge-info" style="padding: 10px 40px; border-radius:15px;">New</label>';
                    } else if (data == 1) {
                        return '<label class="badge badge-primary" style="padding: 10px 27px; border-radius:15px;">Accepted</label>';
                    } else if (data == 2) {
                        return '<label class="badge badge-success" style="padding: 10px 23px; border-radius:15px;">Completed</label>';
                    } else if (data == 3) {
                        return '<label class="badge badge-danger" style="padding: 10px 28px; border-radius:15px;">Declined</label>';
                    } else if (data == 4) {
                        return '<label class="badge badge-secondary" style="padding: 10px 28px; border-radius:15px;">Canceled</label>';
                    } else if (data == 5) {
                        return '<label class="badge badge-warning" style="padding: 10px 34px; border-radius:15px;">Follow</label>';
                    } else {
                        return '';
                    }
                }
            },
            { "data": "date_coached", "width": "10%" , "orderable": false },
            @if (!Route::is('coaching-canceled') && !Route::is('coaching-due') && !Route::is('coaching-accepted'))
                { "data": "next_date_coached", "width": "12%", "orderable": false  },
            @endif
            @if (Route::is('coaching-due') || Route::is('coaching-accepted'))
                { 
                    "data": "follow_through", 
                    "width": "12%",
                    "orderable": false,
                    "render": function (data, type, row) {
                        if (data == 0) {
                            return '<label class="badge badge-info" style="padding: 10px 40px; border-radius:15px;">No</label>';
                        } else if (data == 1) {
                            return '<label class="badge badge-warning" style="padding: 10px 38px; border-radius:15px;">Yes</label>';
                        } else {
                            return '';
                        }
                    }

                },
            @endif
            {
                "data": null,
                "width": "5%",
                "orderable": false,
                "render": function (data, type, row) {
                    return '<button class="btn btn-outline-success edit_team btn-sm rounded-3" onclick="viewCoaching('+ row.id +')"><i class="fa fa-eye"></i>&nbsp;View</button>&nbsp;&nbsp;';
                }
            },
        ],
        "paging": true,
        "searching": true,
        "info": true,
        "lengthMenu": [10, 25, 50, 75, 100],
        "pageLength": 10,
        "autoWidth": true,
        "sScrollX": "100%"
    });

    $('#add-log select:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent()
        });
    });

    $('#summernote').summernote({
        toolbar: [
            ["style", ["style"]],
            ["font", ["bold", "underline", "clear"]],
            ["fontname", ["fontname"]],
            ["color", ["color"]],
            ["table", ["table"]],
            ["insert", ["link", "picture", "video"]],
        ]
    });


    $('#add-coaching-log-form').submit(function (e) {
        e.preventDefault();

        // Show overlay and loader container
        $('#overlay').show();
        $('#loaderContainer').show();

        var selectedUserId = $('select[name="agent_id"]').val();
        var selectedUserTeamId = $('select[name="agent_id"] option[value="' + selectedUserId + '"]').data('team-id');

        var formData = $(this).serialize() + '&team_id=' + selectedUserTeamId;

        $.ajax({
            url: '/coaching/create', 
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Coaching Log Added Successfully',
                        text: '',
                        icon: 'success',
                    }).then(function () {
                        // Add additional logic if needed after a successful save
                        var table = $('#coaching-table').DataTable();
                        table.ajax.reload(null, false);
                        $('#add-log').modal('hide');
                        document.getElementById("add-user-form").reset();
                    });
                } else {
                    var errorMessage = 'Please add value to the following field(s) before proceeding:\n';
                    $.each(response.error, function(field, messages) {
                        errorMessage += field.charAt(0).toUpperCase() + field.slice(1).replace('_', ' ') + ' is a required field.\n';
                    });
                    Swal.fire({
                        title: 'Failed',
                        text: errorMessage,
                        icon: 'error',
                        showConfirmButton: true,
                    });
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.error(error);
            },
            complete: function () {
                // Hide overlay and loader container
                $('#overlay').hide();
                $('#loaderContainer').hide();
            }
        });
    });
    

});


function showCoachingModal() {
    $("#add-log").modal("show");
}

function closeModal() {
    $("#add-log").modal("hide");
}

function viewCoaching(coaching_log_detail_id)
{
    var coaching_log_detail_id = coaching_log_detail_id;
    $.ajax
    ({
    type:"GET",
    url:"/getCoachingLogDetailById/" + coaching_log_detail_id,
    success:function(response)
        { 
            $("#view-coaching").modal("show");
            $('#viewCoachingForm').html(response);
        }
    });
}

function saveChangesBtn(){
    Swal.fire({
        title: "Are you sure?",
        text: 'Are you sure you want to save changes?',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Save Changes!"
    }).then((result) => {
        if (result.isConfirmed) {
            saveChanges();
        }
    });
}

function saveChanges() {

    var formData = {
        coaching_log_detail_id: $("input[name='coaching_log_detail_id']").val(),
        agent_id: $("input[name='agent_id']").val(),
        category_id: $("#category_id").val(),
        channel: $("#channel_select").val(),
        date_coached: $("#date_coached").val(),
        next_date_coached: $("#next_date_coached").val(),
        goal: $("#goal").summernote('code'),
        reality: $("#reality").summernote('code'),
        option: $("#option").summernote('code'),
    };

    $('#overlay').show();
    $('#loaderContainer').show();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax
    ({
    type:"PUT",
    url:"/coaching/saveChanges/",
    data: formData,
    headers: {
        'X-CSRF-TOKEN': csrfToken
    },
    success: function (response) {
            if (response.success) {
                Swal.fire({
                    title: 'Successful!',
                    text: 'Changes saved successfully',
                    icon: 'success',
                });
                var table = $('#coaching-table').DataTable();
                table.ajax.reload(null,false);
                $('#view-coaching').modal('hide');
            } else {
                Swal.fire({
                    title: 'Failed',
                    text: response.error,
                    icon: 'error',
                    showConfirmButton: true,
                });
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
        complete: function () {
            $('#overlay').hide();
            $('#loaderContainer').hide();
        }
    });
}


function cancelCoachingBtn(coachingLogDetailId){
    var coachingLogDetailId = coachingLogDetailId;
    Swal.fire({
        title: "Are you sure?",
        text: 'Are you sure you want to "Cancel" this coaching log?',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Cancel it!"
    }).then((result) => {
        if (result.isConfirmed) {
            cancelCoaching(coachingLogDetailId);
        }
    });
}


function cancelCoaching(coachingLogDetailId) {
    $('#overlay').show();
    $('#loaderContainer').show();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax
    ({
    type:"PUT",
    url:"/coaching/cancelCoaching/" + coachingLogDetailId,
    processData: false,
    contentType: false,
    headers: {
        'X-CSRF-TOKEN': csrfToken
    },
    success: function (response) {
            if (response.success) {
                Swal.fire({
                    title: 'Successful!',
                    text: response.message,
                    icon: 'success',
                });
                var table = $('#coaching-table').DataTable();
                table.ajax.reload(null,false);
                $('#view-coaching').modal('hide');
            } else {
                Swal.fire({
                    title: 'Failed',
                    text: response.error,
                    icon: 'error',
                    showConfirmButton: true,
                });
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
        complete: function () {
            $('#overlay').hide();
            $('#loaderContainer').hide();
        }
    });
}


function completeCoachingBtn(){
    var checkboxChecked = $("#checkbox_next_date").prop('checked');

    var confirmationMessage = checkboxChecked
        ? "Set a Follow Through For this Coaching Log?"
        : "Are you sure you want to complete coaching?";

    Swal.fire({
        title: "Are you sure?",
        text: confirmationMessage,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: checkboxChecked ? "Yes, Set Follow Through!" : "Yes, Complete Coaching!"
    }).then((result) => {
        if (result.isConfirmed) {
            completeCoaching();
        }
    });
}

function completeCoaching() {
    var formData = {
        coaching_log_detail_id: $("input[name='coaching_log_detail_id']").val(),
        agent_id: $("input[name='agent_id']").val(),
        category_id: $("#category_id").val(),
        objective: $("#objective").val(),
        date_coached: $("#date_coached").val(),
        next_date_coached: $("#next_date_coached").val(),
        qa_score: $("#qa_score").val(),
        goal: $("#goal").summernote('code'),
        reality: $("#reality").summernote('code'),
        option: $("#option").summernote('code'),
        checkbox_next_date: $("#checkbox_next_date").prop('checked'),

    };
    console.log("Date Coached:", $("#date_coached").val());
    $('#overlay').show();
    $('#loaderContainer').show();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax
    ({
    type:"PUT",
    url:"/coaching/completeCoaching/",
    data: formData,
    headers: {
        'X-CSRF-TOKEN': csrfToken
    },
    success: function (response) {
            if (response.success) {
                Swal.fire({
                    title: 'Successful!',
                    text: 'Coaching marked as Completed and saved successfully',
                    icon: 'success',
                });
                var table = $('#coaching-table').DataTable();
                table.ajax.reload(null,false);
                $('#view-coaching').modal('hide');
            } else {
                Swal.fire({
                    title: 'Failed',
                    text: response.error,
                    icon: 'error',
                    showConfirmButton: true,
                });
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        },
        complete: function () {
            $('#overlay').hide();
            $('#loaderContainer').hide();
        }
    });
}

</script>





