<script>
    $(document).ready(function () {
        var isSubmittingLogin = false;

        $(document).on('keydown', function (e) {
            if (e.key === 'Enter' && !isSubmittingLogin) {
                e.preventDefault();
                $('#changePasswordButton').trigger('click');
            }
        });

        $('#changePasswordButton').on('click', function () {
            if (isSubmittingLogin) {
                return;
            }

            if ($('#password').val() === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Password Field cannot be empty',
                    icon: 'error',
                    showConfirmButton: true,
                });
                return;
            }

            // Check if confirm password matches password
            if ($('#c_password').val() !== $('#password').val()) {
                Swal.fire({
                    title: 'Error',
                    text: 'Password and Confirm Password do not match',
                    icon: 'error',
                    showConfirmButton: true,
                });
                return;
            }
            isSubmittingLogin = true;

            $('#overlay').show();
            $('#loaderContainer').show();

            $.ajax({
                url: '/change_password',
                type: 'POST',
                data: $('#passwordResetForm').serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.errors) {
                        Swal.fire({
                            title: 'Failed',
                            text: response.errors,
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    } else if (response.error && response.error.password) {
                        var errorMessage = 'Please correct the following errors:\n';
                        $.each(response.error.password, function(index, message) {
                            errorMessage += '- ' + message + '\n';
                        });
                        Swal.fire({
                            title: 'Failed',
                            text: errorMessage,
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    } else {
                        Swal.fire({
                            title: 'Password Changed Successfully',
                            text: 'Redirecting...',
                            icon: 'success',
                            showConfirmButton: false,
                        });
                        setTimeout(function () {
                            window.location.href = "/dashboard";
                        }, 2000); 
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                },
                complete: function () {
                    isSubmittingLogin = false;
                    $('#overlay').hide();
                    $('#loaderContainer').hide();
                }
            });
        });

        $(document).on('keydown', function (e) {
            if (e.key === 'Enter' && !isSubmittingLogin) {
                e.preventDefault();
                $('#changePasswordButtonEmail').trigger('click');
            }
        });

        $('#changePasswordButtonEmail').on('click', function () {
            if (isSubmittingLogin) {
                return;
            }

            if ($('#password').val() === '') {
                Swal.fire({
                    title: 'Error',
                    text: 'Password Field cannot be empty',
                    icon: 'error',
                    showConfirmButton: true,
                });
                return;
            }

            // Check if confirm password matches password
            if ($('#c_password').val() !== $('#password').val()) {
                Swal.fire({
                    title: 'Error',
                    text: 'Password and Confirm Password do not match',
                    icon: 'error',
                    showConfirmButton: true,
                });
                return;
            }
            isSubmittingLogin = true;

            $('#overlay').show();
            $('#loaderContainer').show();

            $.ajax({
                url: '/resetPassword',
                type: 'POST',
                data: $('#passwordResetForm').serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.errors) {
                        Swal.fire({
                            title: 'Failed',
                            text: response.errors,
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    } else if (response.error && response.error.password) {
                        var errorMessage = 'Please correct the following errors:\n';
                        $.each(response.error.password, function(index, message) {
                            errorMessage += '- ' + message + '\n';
                        });
                        Swal.fire({
                            title: 'Failed',
                            text: errorMessage,
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    } else {
                        Swal.fire({
                            title: 'Password Changed Successfully',
                            text: 'Redirecting...',
                            icon: 'success',
                            showConfirmButton: false,
                        });
                        setTimeout(function () {
                            window.location.href = "{{ config('app.url') }}/";
                        }, 2000); 
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                },
                complete: function () {
                    isSubmittingLogin = false;
                    $('#overlay').hide();
                    $('#loaderContainer').hide();
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const passwordField = document.getElementById('password');
        passwordField.addEventListener('mouseenter', function() {
            passwordField.type = 'text';
        });
        passwordField.addEventListener('mouseleave', function() {
            passwordField.type = 'password';
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const cPasswordField = document.getElementById('c_password');
        cPasswordField.addEventListener('mouseenter', function() {
            cPasswordField.type = 'text';
        });
        cPasswordField.addEventListener('mouseleave', function() {
            cPasswordField.type = 'password';
        });
    });

    function toggleForm(formIdToShow, formIdToHide) {
        document.getElementById(formIdToHide).style.opacity = 0;
        setTimeout(function() {
            document.getElementById(formIdToHide).style.display = "none";
            document.getElementById(formIdToShow).style.display = "block";
            setTimeout(function() {
                document.getElementById(formIdToShow).style.opacity = 1;
            }, 10);
        }, 500);
    }
    
    function forgotUsernameRequest() {
        var email = $('#email2').val();
        var employee_id = $('#employee_id').val();
        if ($('#employee_id').val() === '') {
            Swal.fire({
                title: 'Error',
                text: 'Employee ID Cannot be Empty',
                icon: 'error',
                showConfirmButton: true,
            });
            return;
        }

        if ($('#email2').val() === '') {
            Swal.fire({
                title: 'Error',
                text: 'Email Cannot be Empty',
                icon: 'error',
                showConfirmButton: true,
            });
            return;
        }
        $('#overlay').show();
        $('#loaderContainer').show();

        $.ajax({
            url: '/forgotUsernameRequest',
            type: 'POST',
            data: {
                email: email,
                employee_id: employee_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Submitted Successfully ',
                        text: '',
                        icon: 'success',
                    }).then(function() {
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
                console.error('Error processing request:', error);
            },
            complete: function() {
                $('#overlay').hide();
                $('#loaderContainer').hide();
            }
        });
    }


    function forgotPasswordRequest() {
        var username = $('#username').val();
        var email = $('#email').val();
        if ($('#username').val() === '') {
            Swal.fire({
                title: 'Error',
                text: 'Username Cannot be Empty',
                icon: 'error',
                showConfirmButton: true,
            });
            return;
        }

        if ($('#email').val() === '') {
            Swal.fire({
                title: 'Error',
                text: 'Email Cannot be Empty',
                icon: 'error',
                showConfirmButton: true,
            });
            return;
        }
        $('#overlay').show();
        $('#loaderContainer').show();

        $.ajax({
            url: '/forgotPassword',
            type: 'POST',
            data: {
                email: email,
                username: username
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                if (response.message) {
                    Swal.fire({
                        title: 'Submitted Successfully ',
                        text: '',
                        icon: 'success',
                    }).then(function() {
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
                console.error('Error processing request:', error);
            },
            complete: function() {
                $('#overlay').hide();
                $('#loaderContainer').hide();
            }
        });
    }
</script>