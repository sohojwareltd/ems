<x-emails.base title="Refund Processed">
    <p>Hello {{ $customerName }},</p>
    <p>Your refund has been processed.</p>

    <div class="panel">
        <p style="margin:0 0 6px;"><strong>Order Number:</strong> {{ $orderNumber }}</p>
        <p style="margin:0 0 6px;"><strong>Order Date:</strong> {{ $orderDate }}</p>
        <p style="margin:0 0 6px;"><strong>Refund Date:</strong> {{ $refundDate }}</p>
        <p style="margin:0;"><strong>Refund Amount:</strong> ${{ $refundAmount }}</p>
    </div>

    <div class="panel">
        <p style="margin:0 0 6px;"><strong>Reason:</strong> {{ $refundReason }}</p>
        <p style="margin:0;">{{ $refundInfo }}</p>
    </div>

    <p><strong>Items</strong></p>
    <ul class="list">
        @foreach ($items as $item)
            <li>{{ $item->product_name }} (x{{ $item->quantity }})</li>
        @endforeach
    </ul>

    <div class="btn-wrap">
        <a href="{{ url('/orders/' . $order->id) }}" class="btn">View Order Details</a>
    </div>
</x-emails.base>
