@component('mail::message')
<h1 style="font-family:'Playfair Display',serif; color:#2E2E2E; font-size:24px; font-weight:700; margin-bottom:1em;">
    Welcome, {{ $user->name }}!
</h1>

<p style="font-size:16px; font-family:'Inter',Arial,sans-serif; color:#4A3F35; line-height:1.7; margin-bottom:2em;">
    Thank you for registering at <b>{{ setting('store.name', config('app.name')) }}</b>. We’re excited to have you join our community of book lovers!
</p>

@if(isset($password) && $password)
<p style="font-size:16px; font-family:'Inter',Arial,sans-serif; color:#4A3F35; line-height:1.7; margin-bottom:2em;">
    <b>Your account has been created for you.</b><br>
    <b>Email:</b> {{ $user->email }}<br>
    <b>Temporary Password:</b> {{ $password }}<br>
    Please log in and change your password after your first login.
</p>
@endif

@component('mail::button', ['url' => url('/')])
Browse Our Collection
@endcomponent

<p style="font-size:15px; font-family:'Inter',Arial,sans-serif; color:#4A3F35; line-height:1.7; margin-top:2em;">
    If you have any questions, feel free to reply to this email.<br>
    Happy reading!<br>
    The {{ setting('store.name', config('app.name')) }} Team
</p>
@endcomponent 