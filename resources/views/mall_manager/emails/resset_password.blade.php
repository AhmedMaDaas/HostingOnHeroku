@component('mail::message')
# Introduction
Welcome {{ $data['manager']->name }} <br/>
The body of your message.

@component('mail::button', ['url' => url('mall-manager/resset/password/' . $data['token'])])
Click Here To Resset Your Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent