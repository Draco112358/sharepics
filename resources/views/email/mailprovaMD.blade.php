@component('mail::message')
# Introduction
Benvenuto {{$user->name}}

@component('mail::button', ['url' => route('login')])
Loggati
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
