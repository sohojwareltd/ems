<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Orders Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .summary {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .summary-grid {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .summary-item {
            flex: 1;
            min-width: 200px;
            margin: 5px;
        }
        
        .summary-item strong {
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background: #4472C4;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-processing { background: #d4edda; color: #155724; }
        .status-shipped { background: #cce5ff; color: #004085; }
        .status-delivered { background: #d1e7dd; color: #0f5132; }
        .status-returned { background: #f8d7da; color: #721c24; }
        .status-refunded { background: #e2e3e5; color: #383d41; }
        .status-cancelled { background: #f5c6cb; color: #721c24; }
        .status-completed { background: #d4edda; color: #155724; }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Orders Export Report</h1>
        <p>Generated on: {{ $exportDate }}</p>
        <p>Total Orders: {{ $totalOrders }} | Total Revenue: ${{ number_format($totalRevenue, 2) }}</p>
    </div>
    
    <div class="summary">
        <h3>Export Summary</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <strong>Total Orders:</strong> {{ $totalOrders }}
            </div>
            <div class="summary-item">
                <strong>Total Revenue:</strong> ${{ number_format($totalRevenue, 2) }}
            </div>
            <div class="summary-item">
                <strong>Export Date:</strong> {{ $exportDate }}
            </div>
        </div>
        
        <h4 style="margin: 15px 0 10px 0;">Status Breakdown:</h4>
        <div class="summary-grid">
            @foreach($statusBreakdown as $status => $count)
                <div class="summary-item">
                    <strong>{{ ucfirst($status) }}:</strong> {{ $count }}
                </div>
            @endforeach
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Shipping</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>
                        <strong>{{ $order->user->name ?? 'Guest' }}</strong><br>
                        <small>{{ $order->user->email ?? 'N/A' }}</small>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ ucfirst($order->payment_status) }}</strong><br>
                        <small>{{ $order->payment_method ?? 'N/A' }}</small>
                    </td>
                    <td>
                        <strong>${{ number_format($order->total, 2) }}</strong><br>
                        <small>{{ $order->lines->count() }} items</small>
                    </td>
                    <td>
                        <strong>{{ $order->shipping_method ?? 'N/A' }}</strong><br>
                        <small>{{ $order->tracking ?? 'No tracking' }}</small>
                    </td>
                    <td>
                        <strong>{{ $order->created_at->format('M d, Y') }}</strong><br>
                        <small>{{ $order->created_at->format('H:i') }}</small>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>This report was generated automatically by the MyShop Admin Panel</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html> 