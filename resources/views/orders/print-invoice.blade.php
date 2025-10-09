<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} - EMS</title>
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=Inter:wght@400;500;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #00b22d;
            --primary-dark: #19390b;
            --secondary-color: #a6f2b9;
            --accent-color: #d4c4b7;
            --success-color: #8ba892;
            --danger-color: #b87a7a;
            --warning-color: #d4b483;
            --info-color: #8ba8b5;
            --light-bg: #faf9f7;
            --border-color: #e8e0d8;
            --text-muted: #8a7f72;
            --text-dark: #4a3f35;
            --white: #ffffff;
            --shadow-soft: 0 2px 8px rgba(155, 139, 122, 0.08);
            --shadow-medium: 0 4px 16px rgba(155, 139, 122, 0.12);
            --shadow-strong: 0 8px 32px rgba(155, 139, 122, 0.16);
        }

        html,
        body {
            background: var(--light-bg);
            color: var(--text-dark);
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            line-height: 1.7;
            margin: 0;
            padding: 0;
        }

        .invoice-main {
            max-width: 800px;
            margin: 40px auto;
            background: var(--white);
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(155, 139, 122, 0.10);
            border: 1px solid var(--border-color);
            padding: 0 0 32px 0;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 40px 48px 24px 48px;
            border-bottom: 1px solid var(--border-color);
            background: var(--light-bg);
            border-radius: 18px 18px 0 0;
        }

        .header-left {
            max-width: 60%;
        }

        .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.1rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 0.2em;
        }

        .brand-contact {
            color: var(--text-muted);
            font-size: 1em;
            margin-bottom: 0.2em;
        }

        .header-right {
            text-align: right;
            font-size: 1em;
            color: var(--text-muted);
        }

        .header-right .business-address {
            margin-bottom: 0.2em;
        }

        .header-right .tax-id {
            font-size: 0.97em;
        }

        .invoice-card {
            margin: 32px 48px 0 48px;
            background: var(--white);
            border-radius: 14px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 6px rgba(155, 139, 122, 0.06);
            padding: 0;
        }

        .invoice-meta {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 32px;
            padding: 32px 32px 0 32px;
        }

        .meta-block {
            font-size: 1em;
        }

        .meta-block strong {
            font-weight: 600;
            color: var(--primary-color);
        }

        .meta-block .meta-label {
            color: var(--text-muted);
            font-size: 0.97em;
        }

        .meta-block .meta-value {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1.05em;
        }

        .meta-block .meta-accent {
            color: var(--primary-color);
            font-size: 1.3em;
            font-weight: 700;
        }

        .meta-block .meta-right {
            text-align: right;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 32px 0 0 0;
        }

        .invoice-table th {
            font-family: 'Inter', sans-serif;
            font-size: 0.98em;
            font-weight: 600;
            color: var(--text-muted);
            background: var(--light-bg);
            border-bottom: 1.5px solid var(--border-color);
            padding: 12px 8px;
            text-align: left;
        }

        .invoice-table td {
            padding: 12px 8px;
            font-size: 1em;
            color: var(--text-dark);
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }

        .invoice-table tr:last-child td {
            border-bottom: none;
        }

        .item-name {
            font-weight: 600;
            color: var(--primary-color);
            font-family: 'Playfair Display', serif;
        }

        .item-desc {
            color: var(--text-muted);
            font-size: 0.97em;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin: 24px 48px 0 0;
            float: right;
            min-width: 260px;
        }

        .summary-table {
            width: 100%;
        }

        .summary-table td {
            padding: 7px 0;
            font-size: 1em;
        }

        .summary-table .label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .summary-table .value {
            text-align: right;
            color: var(--primary-color);
            font-weight: 600;
        }

        .summary-table .total-row {
            font-size: 1.2em;
            font-weight: 700;
            color: var(--primary-color);
        }

        .thanks {
            margin: 48px 48px 0 48px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.05em;
        }

        .terms {
            margin: 32px 48px 0 48px;
            color: var(--text-muted);
            font-size: 0.97em;
            border-top: 1px solid var(--border-color);
            padding-top: 16px;
        }

        @media (max-width: 900px) {

            .invoice-main,
            .invoice-header,
            .invoice-card,
            .thanks,
            .terms {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding-left: 16px !important;
                padding-right: 16px !important;
            }

            .invoice-meta {
                grid-template-columns: 1fr;
                gap: 18px;
                padding: 24px 0 0 0;
            }

            .summary {
                margin-right: 0;
            }
        }

        @media print {

            html,
            body {
                background: #fff !important;
                color: #222 !important;
                font-size: 12px;
                margin: 0 !important;
                padding: 0 !important;
            }

            .invoice-main {
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                border-radius: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .invoice-header {
                border-radius: 0 !important;
                background: #fff !important;
                color: #222 !important;
                border-bottom: 1px solid #ccc !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                padding: 24px 16px 12px 16px !important;
            }

            .invoice-card,
            .summary,
            .thanks,
            .terms {
                background: #fff !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                border: none !important;
                margin: 0 !important;
                padding: 0 16px !important;
            }

            .invoice-meta,
            .summary-table,
            .invoice-table {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
            }

            .invoice-table th,
            .invoice-table td {
                background: #fff !important;
                color: #222 !important;
                border-color: #ccc !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .print-button,
            .no-print,
            nav,
            .pagination,
            .footer,
            .header-right .tax-id {
                display: none !important;
            }

            .brand-title,
            .item-name,
            .thanks {
                color: #222 !important;
            }

            .meta-block .meta-accent,
            .summary-table .total-row,
            .summary-table .value {
                color: #222 !important;
            }

            .terms {
                border-top: 1px solid #ccc !important;
                color: #555 !important;
                padding-top: 8px !important;
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
    <button onclick="printInvoice()" class="print-button no-print"
        style="position:fixed;top:24px;right:24px;background:var(--primary-color);color:var(--white);border:none;padding:12px 28px;border-radius:8px;cursor:pointer;font-size:1em;font-family:'Inter',sans-serif;font-weight:600;box-shadow:0 4px 12px rgba(155,139,122,0.10);transition:all 0.3s cubic-bezier(.4,0,.2,1);z-index:1000;">🖨️
        Print Invoice</button>
    <div class="invoice-main">
        <div class="invoice-header">
            <div class="header-left">
                <div class="brand-title">
                    <img src="{{ Storage::url(setting('store.logo')) }}" style="width:155px; height: auto;"
                        alt="Logo" class="img-fluid">
                </div>
                <div class="brand-contact">www.eternareads.com</div>
                <div class="brand-contact">hello@eternareads.com</div>
                <div class="brand-contact">+1 (555) 123-4567</div>
            </div>
            <div class="header-right">
                <div class="business-address">123 Book Street, Literary City</div>
                <div class="business-address">City, State, IN - 000 000</div>
                <div class="tax-id">TAX ID 00XXXX1234XXX</div>
            </div>
        </div>
        <div class="invoice-card">
            <div class="invoice-meta">
                <div class="meta-block">
                    <div class="meta-label">Billed to,</div>
                    <div class="meta-value"><strong>{{ $order->user->name ?? 'Guest Customer' }}</strong></div>
                    <div class="meta-label">{{ $order->shipping_address['address_line_1'] ?? '' }}</div>
                    <div class="meta-label">{{ $order->shipping_address['city'] ?? '' }},
                        {{ $order->shipping_address['country'] ?? '' }}</div>
                    <div class="meta-label">{{ $order->shipping_address['phone'] ?? '' }}</div>
                </div>
                <div class="meta-block">
                    <div class="meta-label">Invoice number</div>
                    <div class="meta-value"><strong>#{{ $order->id }}</strong></div>
                    <div class="meta-label">Reference</div>
                    <div class="meta-value">INV-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="meta-block meta-right">
                    <div class="meta-label">Invoice of ({{ strtoupper($order->currency) }})</div>
                    <div class="meta-accent">${{ number_format($order->total, 2) }}</div>
                </div>
            </div>
            <div class="invoice-meta" style="padding-top:18px;">
                <div class="meta-block">
                    <div class="meta-label">Subject</div>
                    <div class="meta-value">Order #{{ $order->id }}</div>
                </div>
                <div class="meta-block">
                    <div class="meta-label">Invoice date</div>
                    <div class="meta-value">{{ $order->created_at->format('d M, Y') }}</div>
                </div>
                <div class="meta-block">
                    <div class="meta-label">Due date</div>
                    <div class="meta-value">{{ $order->created_at->addDays(15)->format('d M, Y') }}</div>
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
                            <td>${{ number_format($line->price, 2) }}</td>
                            <td>${{ number_format($line->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="summary">
                <table class="summary-table">
                    <tr>
                        <td class="label">Subtotal</td>
                        <td class="value">${{ number_format($order->subtotal ?? $order->total, 2) }}</td>
                    </tr>
                    @if ($order->total_discount > 0)
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
        </div>
        <div class="thanks">Thanks for the business.</div>
        <div class="terms">
            <div><strong>Terms & Conditions</strong></div>
            <div>Please pay within 15 days of receiving this invoice.</div>
        </div>
    </div>
</body>

</html>
