<script>
    $(document).ready(function() {
    
        $('.summernote').summernote({
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol']],
          ]
        });
    
        $('#coaching-table').DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                "url": "/getCoachingData2",
                "type": "GET",
            },
            "columns": [
                { "data": "id", "width": "1%" , "orderable": false },
                { "data": "week", "width": "5%", "orderable": false  }, 
                { "data": "coach", "width": "13%", "orderable": false  },
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
                        } else {
                            return '';
                        }
                    }
                },
                { "data": "date_coached", "width": "10%", "orderable": false },
                { "data": "next_date_coached", "width": "12%", "orderable": false  },
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
                        Swal.fire({
                            title: 'Failed',
                            text: response.error,
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
    
    function viewCoaching(coaching_log_detail_id) {
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

    function acceptCoachingBtn() {
        Swal.fire({
            title: "Are you sure?",
            text: 'Do you want to continue accept this Coaching Log? ',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, I accept it!"
        }).then((result) => {
            if (result.isConfirmed) {
                acceptCoaching();
            }
        });
    }

    function acceptCoaching() {
        var will = $("#will").summernote('code');
        var sanitizedWill = sanitizeHtml(will).replace(/&nbsp;/g, ' ').trim();
        console.log(sanitizedWill);
        if (sanitizedWill.length === 0) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Will is empty. Please write something in the Will area to accept coaching log"
            });
            return;
        } 
        var formData = {
            coaching_log_detail_id: $("input[name='coaching_log_detail_id']").val(),
            agent_id: $("input[name='agent_id']").val(),
            will: $("#will").val()
        };

        $('#overlay').show();
        $('#loaderContainer').show();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax
        ({
        type:"PUT",
        url:"/coaching/acceptCoaching/",
        data: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Successful!',
                        text: 'Coaching Accepted Successfully',
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

    function sanitizeHtml(html) {
        return html.replace(/<\/?[^>]+(>|$)/g, "");
    }


    function declineCoachingBtn(coachingLogDetailId) {
        var coachingLogDetailId = coachingLogDetailId;
        Swal.fire({
            title: "Are you sure?",
            text: 'Are you sure you want to "Decline" this coaching log?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Decline it!",
            html:
                '<input id="swal-input1" class="swal2-input" placeholder=" Can you tell us a reason?" maxlength="255">',
            customClass: {
                input: 'my-swalert-input'
            },
            focusConfirm: false,
            preConfirm: () => {
                return {
                    reason: document.getElementById('swal-input1').value
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                declineCoaching(coachingLogDetailId, result.value.reason);
            }
        });
    }


    function declineCoaching(coachingLogDetailId, reason) {
        $('#overlay').show();
        $('#loaderContainer').show();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        console.log(reason);
        $.ajax({
            type: "PUT",
            url:"/coaching/declineCoaching/" + coachingLogDetailId + "/" + reason,
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
                    table.ajax.reload(null, false);
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

    