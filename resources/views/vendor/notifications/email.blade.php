@component('mail::message')
    {{-- Logo --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ Storage::url(setting('store.logo')) }}" alt="MyShop Logo" height="60">
    </div>

    # Hello {{ $user->name ?? '' }},

    Thank you for signing up with **EMS**!
    Please verify your email address to get started.

    <p style="text-align: center;">
        <a href="{{ $actionUrl }}"
            style="background-color: #00b22d;color:#fff; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Verify
            Email Address</a>
    </p>

    {{-- If you're having trouble clicking the button, copy and paste the URL below into your web browser:

    [{{ $actionUrl }}]({{ $actionUrl }}) --}}

    Thanks,
    **The EMS Team**

    @slot('subcopy')
        If you did not create an account, no further action is required.
    @endslot
@endcomponent
