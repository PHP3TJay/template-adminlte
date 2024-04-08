@component('mail::message')
# Welcome to MySteps

Dear {{ $user->firstname }},

We have created an account for you.

Your username: {{ $user->username }}
Your temporary password: {{ $password }}

Please log in with the provided credentials and change your password.

@component('mail::button', ['url' => env('APP_URL')])
Login
@endcomponent

Thanks,<br>
SMITS ETG
@endcomponent
