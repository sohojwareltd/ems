<x-emails.base title="Verify Your Email Address">
    <p>Hello {{ $user->name }},</p>
    <p>Thank you for registering with {{ setting('store.name', config('app.name')) }}.</p>
    <p>Please verify your email address to complete your account setup.</p>

    <div class="btn-wrap">
        <a href="{{ $url }}" class="btn">Verify Email Address</a>
    </div>

    <div class="panel">
        <p class="muted" style="margin: 0; word-break: break-all;">{{ $url }}</p>
    </div>

    <p class="muted">If you did not create this account, no further action is required.</p>
</x-emails.base>
