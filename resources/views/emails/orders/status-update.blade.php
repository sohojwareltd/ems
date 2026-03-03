<x-emails.base title="Order Status Update">
    <p>Hello {{ $customerName }},</p>
    <p>Your order status has been updated.</p>

    <div class="panel">
        <p style="margin:0 0 6px;"><strong>Order Number:</strong> {{ $orderNumber }}</p>
        <p style="margin:0 0 6px;"><strong>Previous Status:</strong> {{ ucfirst($previousStatus) }}</p>
        <p style="margin:0;"><strong>Current Status:</strong> {{ ucfirst($newStatus) }}</p>
    </div>

    <p>{{ $statusMessage }}</p>

    @if ($newStatus === 'shipped' && $trackingNumber)
        <div class="panel">
            <p style="margin:0 0 6px;"><strong>Tracking Number:</strong> {{ $trackingNumber }}</p>
            <p style="margin:0;"><strong>Shipping Method:</strong> {{ ucfirst($shippingMethod) }}</p>
        </div>
    @endif

    <div class="btn-wrap">
        <a href="{{ url('/user/orders/' . $order->id) }}" class="btn">View Order</a>
    </div>
</x-emails.base>
