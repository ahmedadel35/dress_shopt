@component('mail::message')
# {{__('contact.mail.messFrom')}} {{$userName}} ({{$userMail}})

{{$userMessage}}

@auth
@component('mail::button', ['url' => url('/user/' . auth()->id())])
{{$userName}} {{__('mail.profile')}}
@endcomponent
@endauth
Thanks,<br>
{{ config('app.name') }}
@endcomponent