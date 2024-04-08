<script>
$(document).ready(function() {


    var url = window.location.href;
    var parts = url.split('/');
    var teamId = parts.pop() || parts.pop();

    $('#team-user-table').DataTable({
        "serverSide": true,
        "processing": true,
        "ajax": {
            "url": "/getTeamUsers?team_id=" + teamId,
            "type": "GET",
        },
        "columns": [
            { "data": "employee_id", "width": "25%" },
            { "data": "name", "width": "35%"  },
            { "data": "position", "width": "25%" },
            {
                "data": null,
                "width": "5%",
                "render": function (data, type, row) {
                    //return '<button class="btn btn-outline-success edit_team btn-sm pr-4 pl-4 rounded-3" onclick="openViewModal('+ row.id +')"><i class="fa fa-eye"></i>&nbsp;View</button>&nbsp;&nbsp;';
                    return '<button class="btn btn-outline-danger edit_team btn-sm pr-3 pl-3 rounded-3" onclick="removeUserBtn(' + row.id + ', ' + row.user_id + ', \'' + row.name + '\')"><i class="fa fa-trash"></i>&nbsp;</button>';
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
function showTeamUserModal(){
    var url = window.location.href;
    var parts = url.split('/');
    var teamId = parts.pop() || parts.pop();
    $.ajax({
        url: '/getUsersForTeam/'+teamId,
        type: 'GET',
        success: function(response) {
            var users = response.data;
            var select = $('#add-team-user').find('.select2').eq(1);
            select.empty();
            $.each(users, function(index, user) {
                select.append('<option value="' + user.id + '">' + user.employee_id + ' - '+ user.name + '</option>');
            });
            select.select2();
            $('#add-team-user').modal('show');
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(error);
        }
    });
}

function clodeModal(){
    $('#add-team-user').modal('hide');
}

$('#add-team-user select:not(.normal)').each(function () {
        $(this).select2({
        dropdownParent: $(this).parent()
    });
});

function submitTeamUser() {
    var url = window.location.href;
    var parts = url.split('/');
    var teamId = parts.pop() || parts.pop();
    var selectedUserIds = $('#add-team-user').find('.select2').eq(1).val();
    var positionId = $('#position_id').val();
    // Show overlay and loader container
    $('#overlay').show();
    $('#loaderContainer').show();

    $.ajax({
        url: '/saveTeamUsers',
        type: 'POST',
        data: {
            team_id: teamId,
            position_id: positionId,
            user_ids: selectedUserIds
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Users Added Successfully',
                    text: '',
                    icon: 'success',
                }).then(function() {
                    clodeModal();
                    var table = $('#team-user-table').DataTable();
                    table.ajax.reload(null,false);
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
        error: function(xhr, status, error) {
            console.error('Error saving users:', error);
        },
        complete: function() {
            $('#overlay').hide();
            $('#loaderContainer').hide();
        }
    });
}

function removeUserBtn(team_user_id, user_id,name){
    var userId = user_id;
    var name = name;

    Swal.fire({
        title: "Are you sure?",
        text: 'Once Remove, ' +name + ' will no longer be on this team.',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Remove it!"
    }).then((result) => {
        if (result.isConfirmed) {
            removeUser(team_user_id,user_id);
        }
    });
}

function removeUser(team_user_id, user_id) {
    $('#overlay').show();
    $('#loaderContainer').show();
    var user_id = user_id;
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax
    ({
    type:"POST",
    url:"/removeUser/" + team_user_id + "/" + user_id,
    processData: false,
    contentType: false,
    headers: {
        'X-CSRF-TOKEN': csrfToken
    },
    success: function (response) {
            if (response.success) {
                Swal.fire({
                    title: 'Successful!',
                    text: 'User Remove Successfully',
                    icon: 'success',
                }).then(function() {
                    var table = $('#team-user-table').DataTable();
                    table.ajax.reload(null,false);
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
    
</script>