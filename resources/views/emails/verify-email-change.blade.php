<x-emails.base title="Verify Email Change">
    <p>Hello {{ $user->name }},</p>
    <p>You requested to change your account email to:</p>

    <div class="panel">
        <strong>{{ $new_email }}</strong>
    </div>

    <p>Please confirm this request from the button below.</p>

    <div class="btn-wrap">
        <a href="{{ $verifyUrl }}" class="btn">Confirm Email Change</a>
    </div>

    <p class="muted">This link expires in 24 hours. If this was not you, you can safely ignore this message.</p>
</x-emails.base>
