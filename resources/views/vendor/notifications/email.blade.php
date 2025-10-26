@component('mail::message')
    {{-- Logo --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ Storage::url(setting('store.logo')) }}" alt="MyShop Logo" height="60">
    </div>
    <h1 style="text-align: center;color:#444">Verify Your Account</h1>

    Please confirm you want to use this as your EMS account email address. Once it’s done you will be able to start
    learning!

    <p style="text-align: center;">
        <a href="{{ $actionUrl }}"
            style="background-color: #00b22d;color:#fff; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Verify
            my email </a>
    </p>
@endcomponent
