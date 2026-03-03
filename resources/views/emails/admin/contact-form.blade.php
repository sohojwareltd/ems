<x-emails.base title="New Enquiry Submission">
    <div class="panel">
        <p style="margin:0 0 6px;"><strong>First Name:</strong> {{ $data['first_name'] }}</p>
        <p style="margin:0 0 6px;"><strong>Last Name:</strong> {{ $data['last_name'] }}</p>
        <p style="margin:0 0 6px;"><strong>Email:</strong> {{ $data['email'] }}</p>
        <p style="margin:0 0 6px;"><strong>Phone:</strong> {{ $data['phone_full'] ?? ($data['phone'] ?? 'N/A') }}</p>
        <p style="margin:0 0 6px;"><strong>Category:</strong> {{ $data['contact_category_name'] ?? 'N/A' }}</p>
    </div>

    <p><strong>Message</strong></p>
    <div class="panel">
        {!! nl2br(e($data['message'])) !!}
    </div>
</x-emails.base>
