@component('mail::message')
# {{__('order.mail.yorOrderIsProcc')}}

{{__('order.mail.thankMess')}}<br>

@component('mail::table')

| ----                          | {{__('order.details')}}    |
|:-----------------------------:|:------------------------:|
| #                             | {{$order->enc_id}}       |
| {{__('t.index.overTotal')}}   | LE {{$order->total}}     |
| {{__('orded.mail.qty')}}      | {{$order->qty}}          |
| {{__('order.paymentMethod')}} | {{$paymentMethod}}       |
| {{__('order.mail.payStat')}}  | {{$paymentStatus}}       |
| {{__('auth.E-Mail-Address')}} | {{$userMail}}            |
@endcomponent

@component('mail::button', ['url' => url('/order/track'), 'color' => 'primary'])
{{__('order.tracker')}}
@endcomponent

{{__('order.mail.thanksFooter')}},<br>
{{ config('app.name') }}
@endcomponent