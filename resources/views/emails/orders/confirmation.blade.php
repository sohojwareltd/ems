<x-emails.base title="Order Confirmation">
    <p>Hello {{ $customerName }},</p>
    <p>Thank you for your order. Your order has been received successfully.</p>

    <div class="panel">
        <p style="margin:0 0 6px;"><strong>Order Number:</strong> {{ $orderNumber }}</p>
        <p style="margin:0 0 6px;"><strong>Order Date:</strong> {{ $orderDate }}</p>
        <p style="margin:0;"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    </div>

    <p><strong>Order Items</strong></p>
    <ul class="list">
        @foreach ($items as $item)
            <li>{{ $item->product_name }} (Qty: {{ $item->quantity }}) — ${{ number_format($item->total, 2) }}</li>
        @endforeach
    </ul>

    <table class="clean">
        <tr><td>Subtotal</td><td style="text-align:right;">${{ number_format($order->subtotal, 2) }}</td></tr>
        @if ($order->tax > 0)
            <tr><td>Tax</td><td style="text-align:right;">${{ number_format($order->tax, 2) }}</td></tr>
        @endif
        @if ($order->shipping > 0)
            <tr><td>Shipping</td><td style="text-align:right;">${{ number_format($order->shipping, 2) }}</td></tr>
        @endif
        @if ($order->discount > 0)
            <tr><td>Discount</td><td style="text-align:right;">-${{ number_format($order->discount, 2) }}</td></tr>
        @endif
        <tr><td><strong>Total</strong></td><td style="text-align:right;"><strong>${{ $total }}</strong></td></tr>
    </table>

    @if(setting('store.bank_details'))
        <div class="panel">
            <strong>Bank Details</strong><br>
            {!! nl2br(e(setting('store.bank_details'))) !!}
        </div>
    @endif
</x-emails.base>
