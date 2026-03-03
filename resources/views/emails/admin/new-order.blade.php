<x-emails.base title="New Order Received">
    <p>A new order has been placed and requires review.</p>

    <div class="panel">
        <p style="margin:0 0 6px;"><strong>Order Number:</strong> {{ $orderNumber }}</p>
        <p style="margin:0 0 6px;"><strong>Order Date:</strong> {{ $orderDate }}</p>
        <p style="margin:0 0 6px;"><strong>Payment Method:</strong> {{ $paymentMethod }}</p>
        <p style="margin:0;"><strong>Total:</strong> ${{ $total }}</p>
    </div>

    <p><strong>Customer</strong></p>
    <div class="panel">
        <p style="margin:0 0 6px;"><strong>Name:</strong> {{ $customerName }}</p>
        <p style="margin:0 0 6px;"><strong>Email:</strong> {{ $customerEmail }}</p>
        <p style="margin:0;"><strong>Phone:</strong> {{ $customerPhone }}</p>
    </div>

    <p><strong>Items</strong></p>
    <ul class="list">
        @foreach($items as $item)
            <li>{{ $item->product_name }} (Qty: {{ $item->quantity }}) — ${{ number_format($item->total, 2) }}</li>
        @endforeach
    </ul>

    <div class="btn-wrap">
        <a href="{{ $adminUrl }}" class="btn">Open Order in Admin</a>
    </div>
</x-emails.base>
