@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @include('vendor.mail.html.header')
    @endslot

    {{-- Body --}}
    <div style="padding:24px 0;">
        {{ $slot }}
    </div>

    <div style="width:60px;height:4px;background:linear-gradient(90deg,#D7CCC8,#C5E1A5);border-radius:2px;margin:32px auto 24px auto;"></div>

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
