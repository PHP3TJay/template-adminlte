<script>
    $(document).ready(function () {
        var isSubmittingLogin = false;

        $(document).on('keydown', function (e) {
            if (e.key === 'Enter' && !isSubmittingLogin) {
                $('#loginButton').trigger('click');
            }
        });

        $('#loginButton').on('click', function () {
            if (isSubmittingLogin) {
                return;
            }

            isSubmittingLogin = true;

            $('#overlay').show();
            $('#loaderContainer').show();

            $.ajax({
                url: $('#loginForm').attr('action'),
                type: 'POST',
                data: $('#loginForm').serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        if (response.change_password) {
                            $('#loginButton').removeAttr('id');
                            setTimeout(function () {
                                window.location.href = "/password-reset";
                            }, 4000);
                            Swal.fire({
                                title: 'Login Success',
                                text: 'Redirecting...',
                                icon: 'success',
                                showConfirmButton: true,
                            }).then(function () {
                                window.location.href = "/password-reset";
                            });
                        } else {
                            $('#loginButton').removeAttr('id');
                            setTimeout(function () {
                                window.location.href = "/dashboard";
                            }, 4000);
                            Swal.fire({
                                title: 'Login Success',
                                text: 'Redirecting...',
                                icon: 'success',
                                showConfirmButton: true,
                            }).then(function () {
                                window.location.href = "/dashboard";
                            });
                        }
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
                    isSubmittingLogin = false;
                    $('#overlay').hide();
                    $('#loaderContainer').hide();
                }
            });
        });
    });
</script>