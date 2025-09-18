<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }} - EMS</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #222;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .invoice-main {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 0 0 24px 0;
        }
        .invoice-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .brand-title {
            font-size: 1.7rem;
            font-weight: bold;
            margin-bottom: 0.2em;
        }
        .brand-contact {
            color: #888;
            font-size: 1em;
            margin-bottom: 0.2em;
        }
        .header-right {
            text-align: right;
            font-size: 1em;
            color: #888;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 10px 0;
        }
        .meta-table td {
            vertical-align: top;
            padding: 8px 8px 8px 0;
        }
        .meta-block strong {
            font-weight: 600;
            color: #222;
        }
        .meta-label {
            color: #888;
            font-size: 0.97em;
        }
        .meta-value {
            font-weight: 600;
            color: #222;
            font-size: 1.05em;
        }
        .meta-accent {
            color: #222;
            font-size: 1.2em;
            font-weight: 700;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 24px 0 0 0;
        }
        .invoice-table th {
            font-size: 0.98em;
            font-weight: 600;
            color: #888;
            background: #f7f7f7;
            border-bottom: 1.5px solid #eee;
            padding: 10px 6px;
            text-align: left;
        }
        .invoice-table td {
            padding: 10px 6px;
            font-size: 1em;
            color: #222;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        .invoice-table tr:last-child td {
            border-bottom: none;
        }
        .item-name {
            font-weight: 600;
            color: #222;
        }
        .item-desc {
            color: #888;
            font-size: 0.97em;
        }
        .text-right {
            text-align: right;
        }
        .summary {
            margin: 18px 24px 0 0;
            float: right;
            min-width: 220px;
        }
        .summary-table {
            width: 100%;
        }
        .summary-table td {
            padding: 6px 0;
            font-size: 1em;
        }
        .summary-table .label {
            color: #888;
            font-weight: 500;
        }
        .summary-table .value {
            text-align: right;
            color: #222;
            font-weight: 600;
        }
        .summary-table .total-row {
            font-size: 1.1em;
            font-weight: 700;
            color: #222;
        }
        .thanks {
            margin: 32px 32px 0 32px;
            font-weight: 600;
            color: #222;
            font-size: 1.05em;
        }
        .terms {
            margin: 24px 32px 0 32px;
            color: #888;
            font-size: 0.97em;
            border-top: 1px solid #eee;
            padding-top: 12px;
        }
        td div {
         padding: 1px 5px;
        }
    </style>
</head>
<body>
    <div class="invoice-main">
        <!-- Header -->
        <table class="invoice-header-table">
            <tr>
                <td style="width:60%;">
                    <div class="brand-title">{{ setting('store.name') ?? 'EMS' }}</div>
                    <div class="brand-contact">{{ url('/') }}</div>
                    <div class="brand-contact">{{ setting('store.email') ?? 'hello@eternareads.com' }}</div>
                    <div class="brand-contact">{{ setting('store.phone') ?? '+1 (555) 123-4567' }}</div>
                </td>
                <td class="header-right" style="width:40%;">
                    <div>{{ setting('store.address') ?? 'City, State, IN - 000 000' }}</div>
                    @if(setting('store.tax_id'))
                        <div>TAX ID: {{ setting('store.tax_id') }}</div>
                    @endif
                </td>
            </tr>
        </table>
        <!-- Meta Info: Billed to | Invoice Info | Subject/Date -->
        <table class="meta-table">
            <tr>
                <!-- Billed To -->
                <td style="width:33%;">
                    <div class="meta-label">Billed to,</div>
                    <div class="meta-value"><strong>{{ $order->billing_address['first_name'] . ' ' . $order->billing_address['last_name'] ?? 'Guest Customer' }}</strong></div>
                    <div class="meta-label">{{ $order->billing_address['address_1'] ?? '' }}</div>
                    <div class="meta-label">{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }}, {{ \App\Models\Country::where('code', $order->billing_address['country'])->first( )->name ?? '' }}</div>
                    <div class="meta-label">{{ $order->billing_address['phone'] ?? '' }}</div>
                </td>
                <!-- Invoice Info -->
                <td style="width:30%;">
                    <div class="meta-label">Invoice number</div>
                    <div class="meta-value"><strong>#{{ $order->id }}</strong></div>
                    <div class="meta-label">Reference</div>
                    <div class="meta-value">INV-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                    <div class="meta-label">Invoice of ({{ strtoupper($order->currency) }})</div>
                    <div class="meta-accent">${{ number_format($order->total, 2) }}</div>
                </td>
                <!-- Subject/Date -->
                <td style="width:37%;">
                    <div class="meta-label">Subject</div>
                    <div class="meta-value">Order #{{ $order->id }}</div>
                    <div class="meta-label">Invoice date</div>
                    <div class="meta-value">{{ $order->created_at->format('d M, Y') }}</div>
                    @if($order->payment_status == 'pending')
                        <div class="meta-label">Due date</div>
                        <div class="meta-value">{{ $order->created_at->addDays(15)->format('d M, Y') }}</div>
                    @else
                        <div class="meta-label">Payment status</div>
                        <div class="meta-value" style="text-transform: uppercase;">{{ $order->payment_status }}</div>
                        <div class="meta-label">Payment method</div>
                        <div class="meta-value" style="text-transform: uppercase;">{{ $order->payment_method }}</div>
                        <div class="meta-label">Payment ID</div>
                        <div class="meta-value">{{ $order->payment_intent_id ?? 'N/A' }}</div>
                    @endif
                </td>
            </tr>
        </table>
        <!-- Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Item Detail</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->lines as $line)
                    <tr>
                        <td>
                            <div class="item-name">{{ $line->product_name }}</div>
                            <div class="item-desc">SKU: {{ $line->sku }}</div>
                        </td>
                        <td>{{ $line->quantity }}</td>
                        <td>${{ number_format($line->price, 2) }}</td>
                        <td>${{ number_format($line->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Summary Table -->
        <div class="summary">
            <table class="summary-table">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="value">${{ number_format($order->subtotal ?? $order->total, 2) }}</td>
                </tr>
                @if($order->total_discount > 0)
                    <tr>
                        <td class="label">Discounts</td>
                        <td class="value">-${{ number_format($order->total_discount, 2) }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <td class="label">Total</td>
                    <td class="value">${{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>
        <div style="clear:both;"></div>
        <div class="thanks">Thanks for the business.</div>
     
    </div>
</body>
</html> 