<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - {{ setting('store.name', config('app.name')) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
</head>
<body style="background: #fff; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background: #fff;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px; margin:40px auto; background:#fff; border-radius:18px; box-shadow:0 4px 32px rgba(155,139,122,0.10); border:1px solid #E0E0E0;">
                    <tr>
                        <td style="padding:0 0 0 0;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="center" style="padding:32px 0 12px 0;">
                                        <span style="display:inline-block;vertical-align:middle;">
                                            <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="2" y="7" width="14" height="24" rx="3" fill="#fff" stroke="#D7CCC8" stroke-width="2"/>
                                                <rect x="22" y="7" width="14" height="24" rx="3" fill="#fff" stroke="#D7CCC8" stroke-width="2"/>
                                                <path d="M19 9 Q24 19 19 29" stroke="#D7CCC8" stroke-width="2" fill="none"/>
                                            </svg>
                                        </span>
                                        <span style="font-family:'Playfair Display',serif; color:#2E2E2E; font-size:1.6rem; font-weight:700; letter-spacing:1px; margin-left:10px;">
                                            {{ setting('store.name', config('app.name')) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px; font-family:'Inter',Arial,sans-serif; color:#2E2E2E;">
                            <div style="margin: 32px 0 24px 0; text-align: center;">
                                <h1 style="font-family:'Playfair Display',serif; font-size:2rem; font-weight:700; margin:0 0 8px 0; color:#8D6E63;">
                                    @switch($newStatus)
                                        @case('processing')
                                            🔄 Order Processing
                                            @break
                                        @case('shipped')
                                            📦 Order Shipped
                                            @break
                                        @case('delivered')
                                            ✅ Order Delivered
                                            @break
                                        @case('cancelled')
                                            ❌ Order Cancelled
                                            @break
                                        @case('refunded')
                                            💰 Order Refunded
                                            @break
                                        @default
                                            📋 Order Status Updated
                                    @endswitch
                                </h1>
                                <div style="font-size:1.1rem; color:#6D4C41;">Order #{{ $orderNumber }}</div>
                            </div>

                            <div style="background:#FFF8E1; border:1px solid #FFE0B2; color:#8D6E63; border-radius:8px; padding:18px 20px; margin-bottom:28px; font-size:1rem;">
                                <strong>Status Change:</strong> {{ ucfirst($previousStatus) }} → <strong>{{ ucfirst($newStatus) }}</strong>
                            </div>

                            <div style="background:#fff; border:1.5px solid #D7CCC8; border-radius:12px; padding:24px 20px; margin-bottom:24px;">
                                <h2 style="font-family:'Playfair Display',serif; font-size:1.3rem; color:#6D4C41; margin:0 0 18px 0;">Status Update</h2>
                                <p style="font-size:1rem; line-height:1.6; margin:0 0 16px 0;">{{ $statusMessage }}</p>
                                
                                @if($newStatus === 'shipped' && $trackingNumber)
                                    <div style="background:#F5F5F5; border-radius:8px; padding:16px; margin-top:16px;">
                                        <h3 style="font-family:'Playfair Display',serif; font-size:1.1rem; color:#6D4C41; margin:0 0 12px 0;">Shipping Information</h3>
                                        <div style="font-size:1rem;">
                                            <p style="margin:0 0 8px 0;"><strong>Tracking Number:</strong> {{ $trackingNumber }}</p>
                                            <p style="margin:0 0 8px 0;"><strong>Shipping Method:</strong> {{ ucfirst($shippingMethod) }}</p>
                                            @if($order->shipping_address)
                                                <p style="margin:0 0 8px 0;"><strong>Shipping Address:</strong></p>
                                                <div style="margin-left:16px; color:#6D4C41;">
                                                    {{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}<br>
                                                    {{ $order->shipping_address['address'] }}<br>
                                                    {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['zip'] }}<br>
                                                    {{ $order->shipping_address['country'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div style="background:#F5F5F5; border-radius:8px; padding:18px 20px; margin-bottom:24px;">
                                <h3 style="font-family:'Playfair Display',serif; font-size:1.1rem; color:#6D4C41; margin:0 0 12px 0;">Order Summary</h3>
                                <div style="font-size:1rem;">
                                    <p style="margin:0 0 8px 0;"><strong>Order Date:</strong> {{ $order->created_at->format('M j, Y') }}</p>
                                    <p style="margin:0 0 8px 0;"><strong>Items:</strong> {{ $order->lines->count() }} item(s)</p>
                                    <p style="margin:0 0 8px 0;"><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                                    <p style="margin:0 0 8px 0;"><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                                </div>
                            </div>

                            @if($order->lines->count() > 0)
                                <div style="margin-bottom:24px;">
                                    <h3 style="font-family:'Playfair Display',serif; font-size:1.1rem; color:#6D4C41; margin:0 0 12px 0;">Order Items</h3>
                                    @foreach($order->lines as $item)
                                        <div style="border-bottom:1px solid #E0E0E0; padding:10px 0; display:flex; justify-content:space-between;">
                                            <div>
                                                <strong>{{ $item->product_name }}</strong>
                                                @if($item->variant)
                                                    <br><small>Variant: {{ $item->variant['name'] ?? 'N/A' }}</small>
                                                @endif
                                                <br><small>SKU: {{ $item->sku }}</small>
                                            </div>
                                            <div style="text-align:right;">
                                                <div>Qty: {{ $item->quantity }}</div>
                                                <div>${{ number_format($item->price, 2) }} each</div>
                                                <div><strong>${{ number_format($item->total, 2) }}</strong></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div style="background:#F5F5F5; border-radius:8px; padding:18px 20px; margin-bottom:24px;">
                                <table style="width:100%; font-size:1rem;">
                                    <tr>
                                        <td>Subtotal:</td>
                                        <td style="text-align:right;">${{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    @if($order->tax > 0)
                                        <tr>
                                            <td>Tax:</td>
                                            <td style="text-align:right;">${{ number_format($order->tax, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if($order->shipping > 0)
                                        <tr>
                                            <td>Shipping:</td>
                                            <td style="text-align:right;">${{ number_format($order->shipping, 2) }}</td>
                                        </tr>
                                    @endif
                                    @if($order->discount > 0)
                                        <tr>
                                            <td>Discount:</td>
                                            <td style="text-align:right;">-${{ number_format($order->discount, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr style="font-weight:bold; font-size:1.1rem; border-top:2px solid #E0E0E0;">
                                        <td style="padding-top:10px;">Total:</td>
                                        <td style="text-align:right; padding-top:10px;">${{ number_format($order->total, 2) }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div style="text-align:center; margin:32px 0;">
                                <a href="{{ url('/user/orders/' . $order->id) }}" style="display:inline-block; background:#D7CCC8; color:#2E2E2E; font-family:'Playfair Display',serif; font-size:17px; font-weight:700; line-height:1.5; border-radius:8px; padding:14px 36px; text-decoration:none; box-shadow:0 2px 8px rgba(155,139,122,0.10); border: none; letter-spacing:0.5px;">View Order Details</a>
                            </div>

                            @if($order->notes)
                                <div style="background:#fff; border:1.5px solid #D7CCC8; border-radius:12px; padding:18px 20px; margin-bottom:24px;">
                                    <h3 style="font-family:'Playfair Display',serif; font-size:1.1rem; color:#6D4C41; margin:0 0 12px 0;">Order Notes</h3>
                                    <div style="font-size:1rem;">{{ $order->notes }}</div>
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="center" style="padding:24px 0 12px 0; color:#B0AFAF; font-size:13px; font-family:'Inter',Arial,sans-serif;">
                                        &copy; {{ date('Y') }} {{ setting('store.name', config('app.name')) }}<br>
                                        <span style="color:#B0AFAF;">You received this email because you placed an order with us.</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
