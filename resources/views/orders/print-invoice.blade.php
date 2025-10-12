<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} - EMS</title>
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #00b22d;
            /* Logo & headings */
            --text-dark: #2c2c2c;
            /* Main text */
            --text-muted: #555555;
            /* Secondary text */
            --white: #ffffff;
            --light-bg: #f7f7f7;
            --border-color: #e1e1e1;
        }

        html,
        body {
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            line-height: 1.7;
            background: var(--light-bg);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
        }

        .invoice-main {
            max-width: 820px;
            margin: 40px auto;
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            padding: 32px 48px;
            background: var(--white);
        }

        .brand-title img {
            width: 160px;
            height: auto;
        }

        .brand-contact {
            font-size: 0.95em;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .header-right {
            text-align: right;
            font-size: 0.95em;
        }

        .header-right .business-address {
            color: var(--text-muted);
        }

        .invoice-card {
            padding: 32px 48px;
        }

        .invoice-meta {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }

        .meta-block .meta-label {
            font-size: 0.9em;
            color: var(--text-muted);
        }

        .meta-block .meta-value {
            font-weight: 600;
            font-size: 1.05em;
            color: var(--text-dark);
        }

        .meta-block .meta-accent {
            font-size: 1.4em;
            font-weight: 700;
            color: var(--primary-color);
        }

        .meta-block.meta-right {
            text-align: right;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }

        .invoice-table th {
            text-align: left;
            background: var(--light-bg);
            font-weight: 600;
            color: var(--text-muted);
            padding: 12px 10px;
            font-size: 0.95em;
            border-bottom: 2px solid var(--border-color);
        }

        .invoice-table td {
            padding: 14px 10px;
            font-size: 0.95em;
            color: var(--text-dark);
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }

        .item-name {
            font-weight: 600;
            color: var(--primary-color);
            font-family: 'Playfair Display', serif;
        }

        .item-desc {
            color: var(--text-muted);
            font-size: 0.9em;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            float: right;
            min-width: 260px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 8px 0;
            font-size: 0.95em;
        }

        .summary-table .label {
            color: var(--text-muted);
        }

        .summary-table .value {
            text-align: right;
            font-weight: 600;
            color: var(--text-dark);
        }

        .summary-table .total-row {
            font-size: 1.2em;
            font-weight: 700;
            color: var(--primary-color);
            border-top: 2px solid var(--border-color);
            padding-top: 8px;
        }

        .thanks {
            margin-top: 165px;
            font-weight: 600;
            font-size: 1em;
        }

        .terms {
            margin-top: 12px;
            font-size: 0.9em;
            color: var(--text-muted);
            border-top: 1px solid var(--border-color);
            padding-top: 12px;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            z-index: 1000;
        }

        @media print {
            .print-button {
                display: none !important;
            }

            html,
            body {
                background: #fff !important;
                color: #222 !important;
                font-size: 12px;
            }

            .invoice-main {
                box-shadow: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }
        }
    </style>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1200);
        };

        function printInvoice() {
            window.print();
        }
    </script>
</head>

<body>
    <button onclick="printInvoice()" class="print-button">🖨️ Print Invoice</button>

    <div class="invoice-main">
        <div class="invoice-header">
            <div class="header-left">
                <div class="brand-title">
                    <img src="{{ Storage::url(setting('store.logo')) }}" alt="Logo">
                </div>
                <div class="brand-contact">www.economicsmadesimple.com</div>
                <div class="brand-contact">info@economicsmadesimple.com</div>
            </div>
            <div class="header-right">
                <div class="business-address">
                    Great Portland Street,<br> London, <br> United Kingdom
                </div>
            </div>
        </div>

        <div class="invoice-card">
            <div class="invoice-meta">
                <div class="meta-block">
                    <div class="meta-label">Billed To</div>
                    <div class="meta-value">{{ $order->user->name ?? 'Guest Customer' }}</div>
                    @if (!empty($order->shipping_address['address_line_1']))
                        <div class="meta-label">{{ $order->shipping_address['address_line_1'] }}</div>
                    @endif
                    @if (!empty($order->shipping_address['city']))
                        <div class="meta-label">{{ $order->shipping_address['city'] }},
                            {{ $order->shipping_address['country'] ?? '' }}</div>
                    @endif
                    @if (!empty($order->shipping_address['phone']))
                        <div class="meta-label">{{ $order->shipping_address['phone'] }}</div>
                    @endif
                </div>
                <div class="meta-block">

                </div>
                <div class="meta-block meta-right">
                    <div class="meta-label">Invoice #</div>
                    <div class="meta-value">#{{ $order->id }}</div>
                    <div class="meta-label">Reference</div>
                    <div class="meta-value">INV-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>

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
                    @foreach ($order->lines as $line)
                        <tr>
                            <td>
                                <div class="item-name">{{ $line->product_name }}</div>
                                <div class="item-desc">SKU: {{ $line->sku }}</div>
                            </td>
                            <td>{{ $line->quantity }}</td>
                            <td>£{{ number_format($line->price, 2) }}</td>
                            <td>£{{ number_format($line->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary">
                <table class="summary-table">
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="value">£{{ number_format($order->subtotal ?? $order->total, 2) }}</td>
                    </tr>
                    @if ($order->total_discount > 0)
                        <tr>
                            <td class="label">Discount</td>
                            <td class="value">-£{{ number_format($order->total_discount, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td>Total</td>
                        <td class="mb-3" style="margin-bottom: 20px;">£{{ number_format($order->total, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="thanks">Thank you for your business!</div>
            <div class="terms">Terms & Conditions apply. We value your trust in us and hope to exceed your
                expectations.</div>
        </div>
    </div>
</body>

</html>
