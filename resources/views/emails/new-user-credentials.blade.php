<x-emails.base title="Your Account Has Been Created">
    <p>Hello {{ $user->name }},</p>
    <p>Your account is ready. Use the credentials below to sign in:</p>

    <div class="panel">
        <p style="margin: 0 0 8px;"><strong>Email:</strong> {{ $user->email }}</p>
        <p style="margin: 0;"><strong>Temporary Password:</strong> {{ $password }}</p>
    </div>

    <p>Please change your password after first login.</p>

    <div class="btn-wrap">
        <a href="{{ url('/login') }}" class="btn">Log In</a>
    </div>
</x-emails.base>
