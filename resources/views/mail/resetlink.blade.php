@component('mail::message')

Hi ,

You have requested for resetting your password.

Click below button to reset password.

@component('mail::button', ['url' => $data['url']])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
