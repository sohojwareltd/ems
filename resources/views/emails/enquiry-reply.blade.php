<x-emails.base title="Response to Your Enquiry">
    <p>Dear {{ $enquiry->first_name }} {{ $enquiry->last_name }},</p>
    <p>Thank you for contacting us. Please find our response below.</p>

    <div class="panel">
        {!! nl2br(e($replyMessage)) !!}
    </div>

    <p><strong>Your original enquiry</strong></p>
    <div class="panel">
        <p style="margin:0 0 6px;"><strong>Date:</strong> {{ $enquiry->created_at->format('d M Y H:i') }}</p>
        @if($enquiry->category)
            <p style="margin:0 0 6px;"><strong>Category:</strong> {{ $enquiry->category->name }}</p>
        @endif
        <p style="margin:0;"><strong>Message:</strong><br>{{ $enquiry->message }}</p>
    </div>

    <p class="muted">If you have further questions, feel free to contact us again.</p>
</x-emails.base>
