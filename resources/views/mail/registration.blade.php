@component('mail::message')
# Introduction

Account created !!!!

You are now able to post free ads.

{{$data['url']}}

@component('mail::button', ['url' => $data['url']])
POST AD
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
