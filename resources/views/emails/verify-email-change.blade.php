@component('mail::message')
# Verify Your Email Change

Hello {{ $user->name }},

You have requested to change your email address to **{{ $new_email }}**. To confirm this change, please click the button below:

@component('mail::button', ['url' => $verifyUrl])
Verify Email Change
@endcomponent

This link will expire in 24 hours for security reasons.

**Important:** If you did not request this change, please ignore this email. Your email will remain unchanged.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
