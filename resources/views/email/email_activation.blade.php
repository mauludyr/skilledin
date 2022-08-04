@component('mail::message')
# Introduction

Your Password {{ $data['password'] }}

@component('mail::button', ['url' => env('URL_FE').'/verify/'.$data['token'] ])
Verify
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
