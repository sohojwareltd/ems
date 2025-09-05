<!DOCTYPE html>
<html>
<head>
    <title>Shipping Label #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { margin-bottom: 0; }
        .section { margin-bottom: 24px; }
        .label-box { border: 2px dashed #333; padding: 24px; width: 400px; }
        .barcode { margin-top: 24px; font-size: 24px; letter-spacing: 8px; text-align: center; }
    </style>
</head>
<body>
    <h1>Shipping Label #{{ $order->id }}</h1>
    <div class="label-box">
        <div class="section">
            <strong>Customer:</strong> {{ $order->user->name ?? 'Guest' }}<br>
            <strong>Shipping Address:</strong>
            <div>
                @foreach(($order->shipping_address ?? []) as $key => $value)
                    {{ ucfirst($key) }}: {{ $value }}<br>
                @endforeach
            </div>
        </div>
        <div class="section">
            <strong>Shipping Method:</strong> {{ $order->shipping_method ?? 'N/A' }}<br>
            <strong>Tracking:</strong> {{ $order->tracking ?? 'N/A' }}
        </div>
        <div class="barcode">
            {{-- Placeholder for barcode --}}
            {{ $order->tracking ?? 'N/A' }}
        </div>
    </div>
</body>
</html> 