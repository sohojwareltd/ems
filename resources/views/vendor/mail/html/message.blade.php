@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @include('vendor.mail.html.header')
    @endslot

    {{-- Body --}}
    <div style="padding:24px 0;">
        {{ $slot }}
    </div>

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            <div style="border-top:1px solid #E0E0E0; margin:32px 0 0 0; padding-top:18px;">
                {{ $subcopy }}
            </div>
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @include('vendor.mail.html.footer')
    @endslot
@endcomponent
