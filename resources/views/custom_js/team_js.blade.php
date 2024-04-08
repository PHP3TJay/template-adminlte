<script>

    $(document).ready(function() {

        $('#team-table').DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                "url": "/getTeam",
                "type": "GET",
            },
            "columns": [
                { "data": "id", "width": "1%", "orderable": false  },
                { "data": "name", "width": "15%", "orderable": false  },
                { "data": "description", "width": "15%" , "orderable": false },
                {
                    "data": "status",
                    "width": "10%",
                    "render": function (data, type, row) {
                        if (data == 0) {
                            return '<label class="badge badge-danger" style="padding: 5px 18px; border-radius:15px;">Disabled</label>';
                        } else if (data == 1) {
                            return '<label class="badge badge-success" style="padding: 5px 33px; border-radius:15px;">Active</label>';
                        } else if (data == 2) {
                            return '<label class="badge badge-info" style="padding: 5px 28px; border-radius:15px;">Inactive</label>';
                        } else {
                            return '';
                        }
                    }
                },
                {
                    "data": null,
                    "width": "10%",
                    "render": function (data, type, row) {
                        //return '<button class="btn btn-outline-success edit_team btn-sm pr-4 pl-4 rounded-3" onclick="openViewModal('+ row.id +')"><i class="fa fa-eye"></i>&nbsp;View</button>&nbsp;&nbsp;';
                        return '<a class="btn btn-outline-success edit_team btn-sm pr-4 pl-4 rounded-3" href="/team/'+row.id+'"><i class="fa fa-eye"></i>&nbsp;View</a>&nbsp;&nbsp;';
                    }
                },
            ],
            "createdRow": function(row, data, dataIndex) {
                var statusCell = $(row).find('td[data-status]');
                var status = statusCell.data('status');
                if (status === 'deactivate') {
                    statusCell.addClass('deactivate-class');
                } else if (status === 'active') {
                    statusCell.addClass('active-class');
                } else if (status === 'inactive') {
                    statusCell.addClass('inactive-class');
                }
            },
            //"order": [], // Remove this line to enable server-side sorting
            "paging": true,
            "searching": true,
            "info": true,
            "lengthMenu": [10, 25, 50, 75, 100],
            "pageLength": 10,
            "autoWidth": true,
            "sScrollX": "100%"
        });


        $(function () {
            bsCustomFileInput.init();
        });
    });

    function showTeamModal(){
        $('#add-team').modal('show');
    }

    function openViewModal(team_id) {
        var team_id = team_id;
        $.ajax
        ({
        type:"GET",
        url:"/getTeamById/" + team_id,
        success:function(response)
            { 
                $('#view-team').modal('show');
                $('#viewForm').html(response);
            }
        });
        
    }

    



    function clodeModal(modalId){
        $('#' + modalId).modal('hide');
    }


    function openDeleteModal(modalId) {
        $('#' + modalId).modal('show');
    }

    document.addEventListener('DOMContentLoaded', function () {
        var buttons = document.querySelectorAll('.delete_team');
        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var teamId = button.getAttribute('data-team-id');
                var form = document.getElementById('delete-team-form');
                form.action = '{{ url("team/delete") }}/' + teamId;
                form.elements['team_id'].value = teamId;
                openDeleteModal('delete-team');
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll('.edit_team');
        editButtons.forEach(function(editButton) {
            editButton.addEventListener('click', function() {
                var id = editButton.getAttribute('data-id');
                var name = editButton.getAttribute('data-name');
                var description = editButton.getAttribute('data-description');
                var logo = editButton.getAttribute('data-logo');
                var status = editButton.getAttribute('data-status');
                var form = document.getElementById('edit-team-form');
                document.querySelector('.edit-team-input').value = id;
                form.elements['name'].value = name;
                form.elements['description'].value = description;
                if (logo) {
                    var filename = logo.substring(logo.lastIndexOf('/') + 1);
                    form.elements['logo'].value=filename;
                }
                const active = document.getElementById('active');
                const inactive = document.getElementById('inactive');
                if(status == 1){
                    const active = document.getElementById('active');
                    active.checked = true;
                }
                else
                {
                    const inactive = document.getElementById('inactive');
                    inactive.checked = true;
                }
                openEditModal('edit-team');
            });
        });
    }); 

    function updateTeam(event) {

        event.preventDefault();

        $('#overlay').show();
        $('#loaderContainer').show();

        var formData = new FormData($('#edit-team-form')[0]);
        var name;
        var status;
        var team_id;
        var description;
        var _token;

        for (var pair of formData.entries()) {
            if(pair[0] == "name")
                name = pair[1];
            if(pair[0] == "_token")
                _token = pair[1];
            if(pair[0] == "status")
                status = pair[1];
            if(pair[0] == "team_id")
                team_id = pair[1];
            if(pair[0] == "description")
                description = pair[1]; 
        }

        var dataToSend = {
            _token: _token,
            name: name,
            status: status,
            description: description,
            team_id: team_id
        };
        var formDataJson = JSON.stringify(dataToSend);
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        $.ajax({
            url: '/team/edit',
            type: 'PUT',
            data: formDataJson,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Team Updated Successfully',
                        text: '',
                        icon: 'success',
                    }).then(function () {
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
                console.error(xhr, status, error);
            },
            complete: function () {
                $('#overlay').hide();
                $('#loaderContainer').hide();
            }
        });
    }

    function submitTeamForm() {
        $('#overlay').show();
        $('#loaderContainer').show();

        var formData = new FormData($('#add-team-form')[0]);

        $.ajax({
            url: '{{ route('team.save_team') }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    var table = $('#team-table').DataTable();
                    table.ajax.reload(null,false);
                    Swal.fire({
                        title: 'Team Added Successfully',
                        text: '',
                        icon: 'success',
                    }).then(function () {
                        $('#add-team').modal('hide');
                        document.getElementById("add-team-form").reset();
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
                console.error(error);
            },
            complete: function () {
                $('#overlay').hide();
                $('#loaderContainer').hide();
            }
        });
    }

    function updatePosition() {
        var positionsData = $("#sortablePositions li").map(function() {
            var position_title = $(this).find('input[name^="position_title"]').val();
            var position_id = $(this).find('input[name^="position_id"]').val();
            var isChecked = $(this).find('input[type="checkbox"]').prop("checked");
            return { title: position_title, position_id: position_id, checked: isChecked };
        }).get();
        var team_id = $("input[name='team_id']").val();
        $('#overlay').show();
        $('#loaderContainer').show();

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        var positionsDataJson = JSON.stringify(positionsData);
        var dataToSend = {
            positionsData: positionsData,
            team_id: team_id
        };
        var positionsDataJson = JSON.stringify(dataToSend);

        $.ajax({
            url: '/team/updatePosition',
            type: 'PUT',
            data: positionsDataJson,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            success: function (response) {
                if (response.success) {
                    var table = $('#team-table').DataTable();
                    table.ajax.reload(null,false);
                    Swal.fire({
                        title: 'Team Added Successfully',
                        text: '',
                        icon: 'success',
                    }).then(function () {
                        $('#view-team').modal('hide');
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
                console.error(error);
            },
            complete: function () {
                $('#overlay').hide();
                $('#loaderContainer').hide();
            }
        });
    }

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