<x-emails.base title="Welcome to {{ setting('store.name', config('app.name')) }}">
    <p>Hello {{ $user->name }},</p>
    <p>Welcome to {{ setting('store.name', config('app.name')) }}. We’re glad to have you with us.</p>

    @if(isset($password) && $password)
        <div class="panel">
            <p style="margin: 0 0 8px;"><strong>Email:</strong> {{ $user->email }}</p>
            <p style="margin: 0;"><strong>Temporary Password:</strong> {{ $password }}</p>
        </div>
        <p>Please sign in and change your password after your first login.</p>
    @endif

    <div class="btn-wrap">
        <a href="{{ url('/') }}" class="btn">Visit Store</a>
    </div>
</x-emails.base>
