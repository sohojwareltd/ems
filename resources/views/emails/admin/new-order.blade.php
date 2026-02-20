<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Notification</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #f8f9ff 0%, #f5f8f2 50%, #ffffff 100%); margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background: transparent;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px; margin:40px auto; background:#fff; border-radius:18px; box-shadow:0 12px 36px rgba(15, 23, 42, 0.14); border:1px solid #dbe5d5;">
                    <tr>
                        <td style="padding:0 0 0 0;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="center" style="padding:32px 0 12px 0;">
                                        <span style="display:inline-block;vertical-align:middle;">
                                            <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="2" y="7" width="14" height="24" rx="3" fill="#fff" stroke="#19390b" stroke-width="2"/>
                                                <rect x="22" y="7" width="14" height="24" rx="3" fill="#fff" stroke="#19390b" stroke-width="2"/>
                                                <path d="M19 9 Q24 19 19 29" stroke="#19390b" stroke-width="2" fill="none"/>
                                            </svg>
                                        </span>
                                        <span style="font-family:'Playfair Display',serif; color:#19390b; font-size:1.6rem; font-weight:700; letter-spacing:1px; margin-left:10px;">
                                            {{ setting('store.name', config('app.name')) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px; font-family:'Inter',Arial,sans-serif; color:#1f2937;">
                            <div style="margin: 32px 0 24px 0; text-align: center;">
                                <h1 style="font-family:'Playfair Display',serif; font-size:2rem; font-weight:700; margin:0 0 8px 0; color:#19390b;">🚨 New Order Received!</h1>
                                <div style="font-size:1.1rem; color:#0d1f06;">Order #{{ $orderNumber }}<br>Received on {{ $orderDate }}</div>
                            </div>
                            <div style="background:#f5f8f2; border:1px solid #dbe5d5; color:#19390b; border-radius:8px; padding:18px 20px; margin-bottom:28px; font-size:1rem;">
                                <strong>⚠️ Action Required:</strong> This order requires immediate attention. Please process it as soon as possible.
                            </div>
                            <div style="background:#fff; border:1.5px solid #dbe5d5; border-radius:12px; padding:24px 20px; margin-bottom:24px;">
                                <h2 style="font-family:'Playfair Display',serif; font-size:1.3rem; color:#19390b; margin:0 0 18px 0;">Order Summary</h2>
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <div style="font-size:1rem;">
                                        <p style="margin:0 0 6px 0;"><strong>Order Number:</strong> {{ $orderNumber }}</p>
                                        <p style="margin:0 0 6px 0;"><strong>Order Date:</strong> {{ $orderDate }}</p>
                                        <p style="margin:0 0 6px 0;"><strong>Payment Method:</strong> {{ $paymentMethod }}</p>
                                        <p style="margin:0 0 6px 0;"><strong>Items:</strong> {{ $itemCount }} item(s)</p>
                                    </div>
                                    <div style="text-align:right;">
                                        <h3 style="color:#19390b; margin:0; font-size:1.5rem;">${{ $total }}</h3>
                                        <div style="margin:5px 0; color:#0d1f06;">Total Amount</div>
                                    </div>
                                </div>
                            </div>
                            <div style="background:#f5f8f2; border:1px solid #dbe5d5; border-radius:8px; padding:18px 20px; margin-bottom:24px;">
                                <h3 style="font-family:'Playfair Display',serif; font-size:1.1rem; color:#19390b; margin:0 0 12px 0;">Customer Information</h3>
                                <div style="font-size:1rem;">
                                    <p style="margin:0 0 6px 0;"><strong>Name:</strong> {{ $customerName }}</p>
                                    <p style="margin:0 0 6px 0;"><strong>Email:</strong> {{ $customerEmail }}</p>
                                    <p style="margin:0 0 6px 0;"><strong>Phone:</strong> {{ $customerPhone }}</p>
                                </div>
                            </div>
                            <div style="margin-bottom:24px;">
                                <h3 style="font-family:'Playfair Display',serif; font-size:1.1rem; color:#19390b; margin:0 0 12px 0;">Order Items</h3>
                                @foreach($items as $item)
                                    <div style="border-bottom:1px solid #dbe5d5; padding:10px 0; display:flex; justify-content:space-between;">
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
                            <div style="background:#f5f8f2; border:1px solid #dbe5d5; border-radius:8px; padding:18px 20px; margin-bottom:24px;">
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
                                    <tr style="font-weight:bold; font-size:1.1rem; border-top:2px solid #dbe5d5;">
                                        <td style="padding-top:10px;">Total:</td>
                                        <td style="text-align:right; padding-top:10px;">${{ $total }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div style="display:flex; gap:20px; margin-bottom:24px;">
                                <div style="flex:1; background:#f5f8f2; border:1px solid #dbe5d5; padding:15px; border-radius:8px;">
                                    <h4 style="font-family:'Playfair Display',serif; color:#19390b; margin:0 0 8px 0;">Billing Address</h4>
                                    <div style="font-size:1rem;">
                                        <p style="margin:0 0 6px 0;">{{ $billingAddress['first_name'] }} {{ $billingAddress['last_name'] }}</p>
                                        <p style="margin:0 0 6px 0;">{{ $billingAddress['address'] }}</p>
                                        <p style="margin:0 0 6px 0;">{{ $billingAddress['city'] }}, {{ $billingAddress['state'] }} {{ $billingAddress['zip'] }}</p>
                                        <p style="margin:0 0 6px 0;">{{ $billingAddress['country'] }}</p>
                                    </div>
                                </div>
                                <div style="flex:1; background:#f5f8f2; border:1px solid #dbe5d5; padding:15px; border-radius:8px;">
                                    <h4 style="font-family:'Playfair Display',serif; color:#19390b; margin:0 0 8px 0;">Shipping Address</h4>
                                    <div style="font-size:1rem;">
                                        <p style="margin:0 0 6px 0;">{{ $shippingAddress['first_name'] }} {{ $shippingAddress['last_name'] }}</p>
                                        <p style="margin:0 0 6px 0;">{{ $shippingAddress['address'] }}</p>
                                        <p style="margin:0 0 6px 0;">{{ $shippingAddress['city'] }}, {{ $shippingAddress['state'] }} {{ $shippingAddress['zip'] }}</p>
                                        <p style="margin:0 0 6px 0;">{{ $shippingAddress['country'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @if($order->notes)
                                <div style="background:#fff; border:1.5px solid #dbe5d5; border-radius:12px; padding:18px 20px; margin-bottom:24px;">
                                    <h3 style="font-family:'Playfair Display',serif; font-size:1.1rem; color:#19390b; margin:0 0 12px 0;">Order Notes</h3>
                                    <div style="font-size:1rem;">{{ $order->notes }}</div>
                                </div>
                            @endif
                            <div style="text-align:center; margin:32px 0;">
                                <a href="{{ $adminUrl }}" style="display:inline-block; background:linear-gradient(135deg, #19390b, #0d1f06); color:#ffffff; font-family:'Playfair Display',serif; font-size:17px; font-weight:700; line-height:1.5; border-radius:40px; padding:12px 34px; text-decoration:none; box-shadow:0 6px 18px rgba(0,0,0,0.12); border: none; letter-spacing:0.4px; text-transform:uppercase;">Process Order</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px;">
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td align="center" style="padding:24px 0 12px 0; color:#647067; font-size:13px; font-family:'Inter',Arial,sans-serif;">
                                        &copy; {{ date('Y') }} {{ setting('store.name', config('app.name')) }}<br>
                                        <span style="color:#647067;">You received this email because you are an admin of the shop.</span>
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