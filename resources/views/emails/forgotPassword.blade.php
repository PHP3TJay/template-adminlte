@component('mail::message')
    # Password Reset 

    Dear {{ $user->firstname }},

    We receive a password reset request from your account. Please click the button below or use this link to copy and paste and to reset the password of your account.
    
    
    Link : 
    {{url("reset/".$user->reset_token)}}


    This link will expire after 15 minutes of the time of receipt.

    If you did not make the request for password reset, You may ignore this message and we have your account secured.

@component('mail::button', ['url' => url('reset/'.$user->reset_token)])
    Reset Password
@endcomponent

    Thanks,
    MySteps Admin
@endcomponent
