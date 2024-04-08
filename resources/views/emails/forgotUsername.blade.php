@component('mail::message')
# Username Recovery

Dear {{ $user->firstname }},

A request has been made from MySteps to recover your username.

Your username: {{ $user->username }}

If you did not make the request, You can ignore this message.


Thanks,<br>
MySteps Admin
@endcomponent
