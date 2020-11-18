@component('mail::message')
# Introduction
Welcome {{ $data['admin']->name }} <br/>
The body of your message.

@component('mail::button', ['url' => url('admin/resset/password/' . $data['token'])])
Click Here To Resset Your Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent