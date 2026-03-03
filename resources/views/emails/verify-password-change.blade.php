<x-emails.base title="Verify Password Change">
    <p>Hello {{ $user->name }},</p>
    <p>We received a request to change your account password.</p>

    <div class="btn-wrap">
        <a href="{{ $verifyUrl }}" class="btn">Verify Password Change</a>
    </div>

    <div class="panel">
        <p style="margin: 0;">For security, this verification link expires in 1 hour.</p>
    </div>

    <p class="muted">If you did not request this, ignore this email and your password will remain unchanged.</p>
</x-emails.base>
