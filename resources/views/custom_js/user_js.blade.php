<script>
    // $(function () {
    //     $('.select2').select2()
    // });
    

    $(document).ready(function() {
        
        $('#add-user select:not(.normal)').each(function () {
            $(this).select2({
                dropdownParent: $(this).parent()
            });
        });

        $('#user-table').DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                "url": "/user/data",
                "type": "GET",
            },
            "columns": [
                { "data": "id", "width": "1%" , "orderable": false },
                { "data": "name", "width": "15%", "orderable": false  },
                { "data": "employee_id", "width": "15%", "orderable": false  },
                { "data": "username", "width": "15%" , "orderable": false },
                { "data": "email", "width": "15%" , "orderable": false },
                {
                    "data": "account_status",
                    "width": "10%",
                    "render": function (data, type, row) {
                        if (data === 'deactivate') {
                            return '<label class="badge badge-danger" style="padding: 5px 18px; border-radius:15px;">Deactivated</label>';
                        } else if (data === 'active') {
                            return '<label class="badge badge-success" style="padding: 5px 33px; border-radius:15px;">Active</label>';
                        } else if (data === 'inactive') {
                            return '<label class="badge badge-info" style="padding: 5px 28px; border-radius:15px;">Inactive</label>';
                        } else {
                            return '';
                        }
                    }
                },
                { "data": "created_at", "width": "15%" },
                { "data": "hostname", "width": "15%" },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return '<button class="btn btn-outline-success edit_team btn-sm pr-3 pl-3 rounded-3" style="display: flex; align-items: center; height: 28px;" onclick="viewUser('+ row.id +')"><i class="fa fa-eye" style="margin-right: 2px; font-size: 18px;"></i>&nbsp;View</button>&nbsp;&nbsp;';
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


        //$('.js-example-basic-single').select2();
        $('.select2').select2()


        $(document).on('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#add-user-form').submit();
            }
        });

        $('#add-user select:not(.normal)').each(function () {
            $(this).select2({
                dropdownParent: $(this).parent()
            });
        });

        


        $('#add-user-form').submit(function (e) {
            e.preventDefault();

            // Show overlay and loader container
            $('#overlay').show();
            $('#loaderContainer').show();
            console.log($(this).serialize());
            $.ajax({
                url: '/user/create',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'User Added Successfully',
                            text: '',
                            icon: 'success',
                        }).then(function () {
                            var table = $('#user-table').DataTable();
                            table.ajax.reload(null,false);
                            $('#add-user').modal('hide');
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


    function showUserModal() {
        $("#add-user").modal("show");
    }

    function viewUser(user_id)
    {
        var user_id = user_id;
        $.ajax
        ({
        type:"GET",
        url:"/getUserById/" + user_id,
        success:function(response)
            { 
                $("#viewModal").modal("show");
                $('#viewForm').html(response);
            }
        });
    }

    function resetPasswordBtn(user_id,firstname){
        var userId = user_id;
        var firstname = firstname;

        Swal.fire({
            title: "Are you sure?",
            text: 'Once reset, ' +firstname + ' will have to reset his/her password.',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Reset it!"
        }).then((result) => {
            if (result.isConfirmed) {
                resetPassword(user_id);
            }
        });
    }

    function resetPassword(user_id) {
        $('#overlay').show();
        $('#loaderContainer').show();
        var user_id = user_id;
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax
        ({
        type:"PUT",
        url:"/reset/" + user_id,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Successful!',
                        text: 'New Password Has Been Sent To Email Successfully',
                        icon: 'success',
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


    function unlockAccountBtn(username,firstname){
        var username = username;
        var firstname = firstname;

        Swal.fire({
            title: "Are you sure?",
            text: 'You want to force unlock user ' +firstname + '?',
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Unlock it!"
        }).then((result) => {
            if (result.isConfirmed) {
                unlockAccount(username);
            }
        });
    }

    function unlockAccount(username) {
        $('#overlay').show();
        $('#loaderContainer').show();
        var username = username;
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax
        ({
        type:"PUT",
        url:"/unlock/" + username,
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


    function editUserDetails() {
        
        var saveIcon = '<i class="fa fa-save"></i>';
        document.getElementById("editButton").innerHTML = saveIcon + ' Save Changes';
        document.getElementById("editButton").setAttribute("onclick", "saveChanges()");

        document.getElementById("firstname").removeAttribute("disabled");
        document.getElementById("middlename").removeAttribute("disabled");
        document.getElementById("lastname").removeAttribute("disabled");
        document.getElementById("email").removeAttribute("disabled");
        document.getElementById("account_status").removeAttribute("disabled");
        document.getElementById("employee_id").removeAttribute("disabled");

        var checkboxes = document.querySelectorAll("#permissionSection input[type=checkbox]");
        checkboxes.forEach(function (checkbox) {
            checkbox.disabled = false;
        });


        var actionButtonDivs = document.querySelectorAll(".action-button");
        actionButtonDivs.forEach(function (div) {
            var displayStyle = window.getComputedStyle(div).getPropertyValue("display");
            div.style.display = displayStyle === "none" ? "block" : "none";
        });
        

        var roleSelects = document.querySelectorAll(".position_select");
        roleSelects.forEach(function (select) {
            select.removeAttribute("disabled");
        });

        var teamSelects = document.querySelectorAll(".team-select");
        teamSelects.forEach(function (select) {
            select.removeAttribute("disabled");
        });

        var checkboxes = document.querySelectorAll("#permissionSection input[type=checkbox]");
        checkboxes.forEach(function (checkbox) {
            checkbox.disabled = false;
        });
    }

    function saveChanges() {
        var formData = {
            user_id: document.getElementById("user_id").value,
            firstname: document.getElementById("firstname").value,
            middlename: document.getElementById("middlename").value,
            lastname: document.getElementById("lastname").value,
            email: document.getElementById("email").value,
            account_status: document.getElementById("account_status").value,
        };

        // Collect data from permission checkboxes
        var permissionCheckboxes = document.querySelectorAll("#permissionSection input[type=checkbox]");
        var permissions = [];
        permissionCheckboxes.forEach(function (checkbox) {
            permissions.push({
                module_id: checkbox.dataset.moduleId, 
                checked: checkbox.checked,
            });
        });

        formData.permissions = permissions;

        var roleTeamRows = document.querySelectorAll(".role-team-div");
        var roles = [];
        var teams = [];

        roleTeamRows.forEach(function (row) {
            var roleSelect = row.querySelector(".role-select");
            var teamSelect = row.querySelector(".team-select");
            if (roleSelect && roleSelect.value !== null) {
                roles.push({
                    role_id: roleSelect.value,
                });
            }
            if (teamSelect && teamSelect.value !== null) {
                teams.push({
                    team_id: teamSelect.value,
                });
            }
        });

        formData.roles = roles;
        formData.teams = teams;
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $.ajax
        ({
            type:"PUT",
            url:"/saveChanges",
            processData: false,
            contentType: 'application/json',
            data: JSON.stringify(formData),
            dataType: 'json',
            headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function (response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Successful!',
                        text: 'Account updated successfully',
                        icon: 'success',
                    }).then(function () {
                        var table = $('#user-table').DataTable();
                        table.ajax.reload(null,false);
                        $('#viewModal').modal('hide');
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




    function toggleTeamRoleRow(container) {
        var addButton = container.querySelector('.btn-success');
        if (addButton) {
            addTeamRole(container);
        } else {
            removeTeamRoleRow(container);
        }
    }

    function addTeamRole(container) {
        var newDiv = container.cloneNode(true);
        var addButton = newDiv.querySelector('.btn-success');
        if (addButton) {
            addButton.innerHTML = '-';
            addButton.className = 'btn btn-danger btn-sm';
            addButton.onclick = function() {
                removeTeamRoleRow(newDiv);
            };
        }
        container.parentNode.appendChild(newDiv);
    }

    function removeTeamRoleRow(container) {
        container.parentNode.removeChild(container);
    }


    $(document).ready(function(){
        $('#team_select').change(function(){
            var teamId = $(this).val();
            if(teamId){
                $.ajax({
                    url: '{{ config('app.url') }}/get-team-position',
                    type: 'GET',
                    data: {team_id: teamId},
                    dataType: 'json',
                    success: function(data){
                        var positions = data.positions;
                        $('#position_select').empty();
                        $('#position_select').append('<option value=""> Select Position </option>');
                        positions.forEach(function(position) {
                            $('#position_select').append('<option value="'+ position.id +'">'+ position.title +'</option>');
                        });
                    }
                });
            }else{
                $('#position_select').empty();
                $('#position_select').append('<option value=""> Select Position </option>');
            }
        });
    });

    $(document).ready(function(){
        $('#region_select').change(function(){
            var region_name = $(this).val();
            if(region_name){
                $.ajax({
                    url: '{{ config('app.url') }}/get-mypat-site-address',
                    type: 'GET',
                    data: {region_name: region_name},
                    dataType: 'json',
                    success: function(data){
                        var site_addresses = data.site_addresses;
                        $('#site_address_select').empty();
                        $('#site_address_select').append('<option value=""> Select Site Address </option>');
                        site_addresses.forEach(function(site_address) {
                            $('#site_address_select').append('<option value="'+ site_address.site_name +'">'+ site_address.site_name +'</option>');
                        });
                    }
                });
            }else{
                $('#site_address_select').empty();
                $('#site_address_select').append('<option value=""> Select Site Address </option>');
            }
        });
    });


    

    

</script>