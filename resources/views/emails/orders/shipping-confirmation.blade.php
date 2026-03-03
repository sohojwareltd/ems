<x-emails.base title="Your Order Has Shipped">
    <p>Hello {{ $customerName }},</p>
    <p>Your order is now on the way.</p>

    <div class="panel">
        <p style="margin:0 0 6px;"><strong>Order Number:</strong> {{ $orderNumber }}</p>
        <p style="margin:0 0 6px;"><strong>Shipping Method:</strong> {{ ucfirst($shippingMethod) }}</p>
        <p style="margin:0;"><strong>Estimated Delivery:</strong> {{ $estimatedDelivery }}</p>
    </div>

    @if($trackingNumber)
        <div class="panel">
            <p style="margin:0;"><strong>Tracking Number:</strong> {{ $trackingNumber }}</p>
        </div>
    @endif

    <div class="panel">
        <p style="margin:0 0 6px;"><strong>Shipping Address</strong></p>
        <p style="margin:0;">
            {{ $shippingAddress['first_name'] ?? '' }} {{ $shippingAddress['last_name'] ?? '' }}<br>
            {{ $shippingAddress['address'] ?? '' }}<br>
            {{ $shippingAddress['city'] ?? '' }}, {{ $shippingAddress['state'] ?? '' }} {{ $shippingAddress['zip'] ?? '' }}<br>
            {{ $shippingAddress['country'] ?? '' }}
        </p>
    </div>

    <div class="btn-wrap">
        @if($trackingUrl)
            <a href="{{ $trackingUrl }}" class="btn" target="_blank">Track Package</a>
        @endif
        <a href="{{ url('/user/orders/' . $order->id) }}" class="btn">View Order</a>
    </div>
</x-emails.base>
