<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Confirmation - {{ setting('store.name', config('app.name')) }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: {{ setting('mail.primary_color', '#9B8B7A') }};
      --accent-bg: #f5f5f5;
      --highlight: #e53935;
      --text-light: #666;
      --border-color: #E0E0E0;
    }
    body {
      font-family: 'Inter', Arial, sans-serif;
      background: #faf9f7;
      color: #333;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 4px 32px rgba(155,139,122,0.10);
      border: 1px solid var(--border-color);
      padding: 40px 32px;
    }
    .header {
      text-align: center;
      margin-bottom: 32px;
    }
    .header .brand {
      font-family: 'Playfair Display', serif;
      color: var(--primary);
      font-size: 2rem;
      font-weight: 700;
      letter-spacing: 1px;
      margin-bottom: 8px;
    }
    .header .order-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: #2E2E2E;
      font-weight: 700;
      margin-bottom: 0.5em;
    }
    .header .order-meta {
      color: var(--text-light);
      font-size: 1rem;
      margin-bottom: 0.5em;
    }
    .divider {
      width: 60px;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), #A8B5A0);
      border-radius: 2px;
      margin: 32px auto 24px auto;
    }
    .addresses {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      margin-bottom: 32px;
    }
    .address {
      flex: 1;
      min-width: 220px;
      font-size: 15px;
    }
    .address h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1rem;
      color: var(--primary);
      margin-bottom: 10px;
      font-weight: 700;
      text-transform: uppercase;
    }
    .order-status {
      margin-bottom: 18px;
      font-size: 1.1rem;
    }
    .order-status strong {
      color: var(--primary);
    }
    .order-items {
      margin-bottom: 32px;
    }
    .order-items h3 {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--primary);
      margin-bottom: 10px;
      font-weight: 700;
    }
    .item {
      padding: 15px 0;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }
    .item:last-child {
      border-bottom: none;
    }
    .item-details {
      flex: 2;
    }
    .item-details strong {
      font-size: 1rem;
      color: #2E2E2E;
    }
    .item-details small {
      color: var(--text-light);
    }
    .item-qty {
      flex: 1;
      text-align: right;
      font-size: 15px;
      color: #444;
    }
    .totals {
      margin-bottom: 32px;
    }
    .totals table {
      width: 100%;
      border-collapse: collapse;
    }
    .totals td {
      padding: 8px 10px;
      border-bottom: 1px solid #eee;
      font-size: 15px;
    }
    .totals .total td {
      font-weight: bold;
      border-top: 2px solid var(--border-color);
      color: var(--primary);
      font-size: 1.1rem;
    }
    .notes {
      background: var(--accent-bg);
      border-left: 4px solid var(--primary);
      border-radius: 8px;
      padding: 16px 20px;
      margin-bottom: 32px;
      color: #4A3F35;
      font-size: 15px;
    }
    .thank-you {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--primary);
      margin: 32px 0 16px 0;
      text-align: center;
      font-weight: 700;
    }
    .bank-details {
      background: var(--accent-bg);
      border-radius: 8px;
      padding: 16px 20px;
      margin-bottom: 32px;
      color: #4A3F35;
      font-size: 15px;
    }
    .footer {
      text-align: center;
      font-size: 13px;
      color: #B0AFAF;
      border-top: 1px solid var(--border-color);
      padding-top: 20px;
      margin-top: 32px;
    }
    @media (max-width: 700px) {
      .container { padding: 20px 5px; }
      .addresses { flex-direction: column; gap: 16px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="brand">{{ setting('store.name', config('app.name')) }}</div>
      <div class="order-title">Thank You for Your Order!</div>
      <div class="order-meta">Order #{{ $orderNumber }} | Date: {{ $orderDate }}</div>
    </div>
    <div class="divider"></div>
    <div class="order-status">
      <strong>Status:</strong> <span>{{ ucfirst($order->status) }}</span>
    </div>
    <div class="addresses">
      <div class="address">
        <h3>Billing Address</h3>
        <p>{{ $order->billing_address['first_name'] }} {{ $order->billing_address['last_name'] }}</p>
        <p>{{ $order->billing_address['address'] }}</p>
        <p>{{ $order->billing_address['city'] }}, {{ $order->billing_address['state'] }} {{ $order->billing_address['zip'] }}</p>
        <p>{{ $order->billing_address['country'] }}</p>
        <p>Email: {{ $order->billing_address['email'] }}</p>
        <p>Phone: {{ $order->billing_address['phone'] }}</p>
      </div>
      <div class="address">
        <h3>Shipping Address</h3>
        <p>{{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}</p>
        <p>{{ $order->shipping_address['address'] }}</p>
        <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['zip'] }}</p>
        <p>{{ $order->shipping_address['country'] }}</p>
      </div>
    </div>
    <div class="order-items">
      <h3>Order Items</h3>
      @if (!empty($items) && (is_array($items) || is_object($items)))
        @foreach ($items as $item)
          <div class="item">
            <div class="item-details">
              <strong>{{ $item->product_name }}</strong>
              @if ($item->variant)
                <br><small>Variant: {{ $item->variant['name'] ?? 'N/A' }}</small>
              @endif
              <br><small>SKU: {{ $item->sku }}</small>
            </div>
            <div class="item-qty">
              <div>Qty: {{ $item->quantity }}</div>
              <div>${{ number_format($item->price, 2) }} each</div>
              <div><strong>${{ number_format($item->total, 2) }}</strong></div>
            </div>
          </div>
        @endforeach
      @else
        <p>No items found in this order.</p>
      @endif
    </div>
    <div class="totals">
      <table>
        <tr>
          <td>Subtotal:</td>
          <td style="text-align: right;">${{ number_format($order->subtotal, 2) }}</td>
        </tr>
        @if ($order->tax > 0)
        <tr>
          <td>Tax:</td>
          <td style="text-align: right;">${{ number_format($order->tax, 2) }}</td>
        </tr>
        @endif
        @if ($order->shipping > 0)
        <tr>
          <td>Shipping:</td>
          <td style="text-align: right;">${{ number_format($order->shipping, 2) }}</td>
        </tr>
        @endif
        @if ($order->discount > 0)
        <tr>
          <td>Discount:</td>
          <td style="text-align: right;">-${{ number_format($order->discount, 2) }}</td>
        </tr>
        @endif
        <tr class="total">
          <td>Total:</td>
          <td style="text-align: right;">${{ $total }}</td>
        </tr>
      </table>
    </div>
    @if ($order->notes)
      <div class="notes">
        <strong>Order Notes:</strong><br>
        {{ $order->notes }}
      </div>
    @endif
    {{-- <div class="thank-you">
      Thank you for your business!<br>
      Please pay within 15 days of receiving this invoice.
    </div> --}}
    @if(setting('store.bank_details'))
      <div class="bank-details">
        <strong>Bank Details</strong><br>
        {!! nl2br(e(setting('store.bank_details'))) !!}
      </div>
    @endif
    <div class="footer">
      &copy; {{ date('Y') }} {{ setting('store.name', config('app.name')) }}<br>
      <span>You received this email because you placed an order at our shop.</span><br>
      @if(setting('store.email'))
        <span>Contact: {{ setting('store.email') }}</span>
      @endif
    </div>
  </div>
</body>
</html>
