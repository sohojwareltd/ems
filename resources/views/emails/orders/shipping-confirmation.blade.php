<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Shipped</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .tracking-info {
            background: #fff;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .tracking-number {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }
        .order-details {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .shipping-address {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .estimated-delivery {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Your Order Has Been Shipped!</h1>
        <p>Order #{{ $orderNumber }}</p>
    </div>

    <div class="tracking-info">
        <h2>Tracking Information</h2>
        @if($trackingNumber)
            <div class="tracking-number">{{ $trackingNumber }}</div>
            <p><strong>Shipping Method:</strong> {{ ucfirst($shippingMethod) }}</p>
            @if($trackingUrl)
                <a href="{{ $trackingUrl }}" class="btn" target="_blank">Track Your Package</a>
            @endif
        @else
            <p>Your order has been shipped. Tracking information will be available soon.</p>
        @endif
    </div>

    <div class="estimated-delivery">
        <h3>📦 Estimated Delivery</h3>
        <p><strong>{{ $estimatedDelivery }}</strong></p>
        <p>We'll send you another email when your package is delivered.</p>
    </div>

    <div class="order-details">
        <h3>Order Summary</h3>
        <p><strong>Order Number:</strong> {{ $orderNumber }}</p>
        <p><strong>Shipping Method:</strong> {{ ucfirst($shippingMethod) }}</p>
        <p><strong>Items:</strong> {{ $order->lines->count() }} item(s)</p>
    </div>

    <div class="shipping-address">
        <h3>Shipping Address</h3>
        <p>{{ $shippingAddress['first_name'] }} {{ $shippingAddress['last_name'] }}</p>
        <p>{{ $shippingAddress['address'] }}</p>
        <p>{{ $shippingAddress['city'] }}, {{ $shippingAddress['state'] }} {{ $shippingAddress['zip'] }}</p>
        <p>{{ $shippingAddress['country'] }}</p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        @if($trackingUrl)
            <a href="{{ $trackingUrl }}" class="btn" target="_blank">Track Package</a>
        @endif
        <a href="{{ route('order.details', $order->id) }}" class="btn btn-secondary">View Order Details</a>
    </div>

    <div class="footer">
        <p>If you have any questions about your shipment, please contact us at:</p>
        <p><strong>Email:</strong> support@yourstore.com</p>
        <p><strong>Phone:</strong> (555) 123-4567</p>
        <p>Thank you for your patience!</p>
    </div>
</body>
</html> 