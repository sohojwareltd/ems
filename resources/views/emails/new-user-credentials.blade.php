@component('mail::message')
# Welcome, {{ $user->name }}!

Your account has been created for you at <strong>{{ setting('store.name', config('app.name')) }}</strong>.
<br>
<br>
<strong>Email:</strong> {{ $user->email }}  
<br>
<strong>Temporary Password:</strong> {{ $password }}

<br>
<br>
Please log in and change your password after your first login.

@component('mail::button', ['url' => url('/login')])
Log In
@endcomponent

If you have any questions, feel free to reply to this email.

Thanks,<br>
The <strong>{{ setting('store.name', config('app.name')) }}</strong> Team
@endcomponent 