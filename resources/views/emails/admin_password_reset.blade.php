@component('mail::message')
    # Password Reset 

    Dear {{ $user->firstname }},

    A request has been made from the admin to reset your password.

    Your new password : {{ $user->newPassword}}

    If you did not make the request for password reset, Please report to admin immediately.

    Thanks,
    SMITS ETG
@endcomponent
