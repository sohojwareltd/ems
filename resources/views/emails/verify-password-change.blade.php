@component('mail::message')
# Verify Your Password Change

Hello {{ $user->name }},

You have requested to change your password. To confirm this change, please click the button below:

@component('mail::button', ['url' => $verifyUrl])
Verify Password Change
@endcomponent

This link will expire in 1 hour for security reasons.

**Important:** If you did not request this change, please ignore this email. Your password will remain unchanged.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
