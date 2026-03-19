<x-emails.base :title="$adminEmail->subject">
    <p>Hello,</p>

    <div>{!! $adminEmail->body !!}</div>
</x-emails.base>
