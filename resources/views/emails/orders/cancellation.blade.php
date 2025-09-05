<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Order Cancelled - #{{ $orderNumber }}</title>
</head>
<body style="margin:0; padding:20px; font-family:Arial, sans-serif; color:#333; background-color:#ffffff; max-width:700px; margin:0 auto;">

  <div style="margin-bottom:10px;">
    <h1 style="margin:0; font-size:24px; color:#e11d48;">❌ Order Cancelled</h1>
    <p style="margin:5px 0; color:#64748b;">Order #: {{ $orderNumber }}</p>
    <p><strong>Date:</strong> {{ $orderDate }}</p>
    <p><strong>Cancelled on:</strong> {{ $cancellationDate }}</p>
    <p><strong>Status:</strong> <span style="color:#e11d48;">Cancelled</span></p>
  </div>

  <div style="padding:12px 15px; background-color:#fff5f5; border:1px solid #fecaca; border-radius:4px; margin-bottom:20px;">
    <p style="margin:0;"><strong>Reason:</strong> {{ $cancellationReason }}</p>
  </div>

  <div style="padding:12px 15px; background-color:#f8fafc; border-radius:4px; margin-bottom:20px;">
    <p style="margin:0;">{{ $refundInfo }}</p>
  </div>

  <h3 style="font-size:14px; color:#4f46e5;">Items Ordered</h3>
  <ul style="padding-left:18px; font-size:14px;">
    @foreach ($items as $item)
      <li>{{ $item->product_name }} (x{{ $item->quantity }})</li>
    @endforeach
  </ul>

  <a href="{{ url('/orders/' . $order->id) }}" style="padding:12px 24px; background-color:#6c757d; color:#fff; text-decoration:none; border-radius:5px; display:inline-block; margin-top:20px;">View Order Details</a>

</body>
</html>
