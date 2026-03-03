<x-emails.base title="Email Verification Test">
    <p>This is a test email from {{ setting('store.name', config('app.name')) }}.</p>
    <p>If you received this message, email delivery is working correctly.</p>
</x-emails.base>
