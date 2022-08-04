@component('mail::message')
# Reset Password

@component('mail::button', ['url' => env('URL_FE').'/reset-password/'.$data ])
Click Here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
