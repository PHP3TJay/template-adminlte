@component('mail::message')
# MySteps Coaching Assignment

Hi! {{ $coaching->agent_firstname }},

Your coach {{ $coaching->coach_firstname }} created a coaching log with you. <br>
Categories:  {{ $coaching->category_name }}<br>
Coaching Date: {{ $coaching->date_coached}}<br>

Please log in to MySteps to view more details

@component('mail::button', ['url' => env('APP_URL')])
Login
@endcomponent

Thanks,<br>
MySteps
@endcomponent
