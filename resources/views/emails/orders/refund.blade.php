<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Refund Processed - #{{ $orderNumber }}</title>
</head>
<body style="margin:0; padding:20px; font-family:Arial, sans-serif; color:#333; background-color:#ffffff; max-width:700px; margin:0 auto;">

  <div style="margin-bottom:10px;">
    <h1 style="margin:0; font-size:24px; color:#22c55e;">💰 Refund Processed</h1>
    <p style="margin:5px 0; color:#64748b;">Order #: {{ $orderNumber }}</p>
    <p><strong>Date:</strong> {{ $orderDate }}</p>
    <p><strong>Refund Date:</strong> {{ $refundDate }}</p>
    <p><strong>Status:</strong> <span style="color:#22c55e;">Refunded</span></p>
  </div>

  <div style="padding:12px 15px; background-color:#f0fdf4; border:1px solid #bbf7d0; border-radius:4px; margin-bottom:20px;">
    <p style="margin:0;"><strong>Amount Refunded:</strong> ${{ $refundAmount }}</p>
    <p style="margin:0;"><strong>Reason:</strong> {{ $refundReason }}</p>
  </div>

  <div style="padding:12px 15px; background-color:#f8fafc; border-radius:4px; margin-bottom:20px;">
    <p style="margin:0;">{{ $refundInfo }}</p>
  </div>

  <h3 style="font-size:14px; color:#4f46e5;">Items Refunded</h3>
  <ul style="padding-left:18px; font-size:14px;">
    @foreach ($items as $item)
      <li>{{ $item->product_name }} (x{{ $item->quantity }})</li>
    @endforeach
  </ul>

  <a href="{{ url('/orders/' . $order->id) }}" style="padding:12px 24px; background-color:#6c757d; color:#fff; text-decoration:none; border-radius:5px; display:inline-block; margin-top:20px;">View Order Details</a>

</body>
</html>
