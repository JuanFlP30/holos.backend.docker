<x-mail::message>
{{ __('auth.forgot.line') }}

<x-mail::button :url="env('APP_FRONTEND_URL') . '/auth.html#/reset-password?code=12345234234'">
{{ __('auth.forgot.button') }}
</x-mail::button>

{{ __('thanks')}},<br>
{{ config('app.name') }}
</x-mail::message>
