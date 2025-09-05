<div class="space-y-6">
    {{-- Order Information --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-receipt-percent class="w-5 h-5 mr-2" />
            Order Information
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Order #</dt>
                <dd class="text-lg font-bold text-primary-600">{{ $record->id }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                <dd class="text-sm text-gray-900">{{ $record->created_at->format('M j, Y g:i A') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($record->status === 'pending') bg-primary-100 text-primary-800
                        @elseif($record->status === 'paid') bg-success-100 text-success-800
                        @elseif($record->status === 'failed') bg-danger-100 text-danger-800
                        @elseif($record->status === 'refunded') bg-warning-100 text-warning-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($record->status) }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Currency</dt>
                <dd class="text-sm text-gray-900">{{ $record->currency ?? 'USD' }}</dd>
            </div>
        </div>
    </div>

    {{-- Customer Information --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-user class="w-5 h-5 mr-2" />
            Customer Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                <dd class="text-sm text-gray-900">{{ $record->user->name ?? 'Guest' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="text-sm text-gray-900">{{ $record->user->email ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                <dd class="text-sm text-gray-900">{{ $record->user->phone ?? 'N/A' }}</dd>
            </div>
        </div>
    </div>

    {{-- Shipping Address --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-truck class="w-5 h-5 mr-2" />
            Shipping Address
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php $shipping = $record->shipping_address ?? []; @endphp
            <div>
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="text-sm text-gray-900">{{ ($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="text-sm text-gray-900">{{ $shipping['email'] ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                <dd class="text-sm text-gray-900">{{ $shipping['phone'] ?? 'N/A' }}</dd>
            </div>
            <div class="md:col-span-3">
                <dt class="text-sm font-medium text-gray-500">Company</dt>
                <dd class="text-sm text-gray-900">{{ $shipping['company'] ?? 'N/A' }}</dd>
            </div>
            <div class="md:col-span-3">
                <dt class="text-sm font-medium text-gray-500">Address</dt>
                <dd class="text-sm text-gray-900">
                    {{ $shipping['address_line_1'] ?? '' }}<br>
                    @if(!empty($shipping['address_line_2']))
                        {{ $shipping['address_line_2'] }}<br>
                    @endif
                    {{ ($shipping['city'] ?? '') . ', ' . ($shipping['state'] ?? '') . ' ' . ($shipping['postal_code'] ?? '') }}<br>
                    {{ $shipping['country'] ?? '' }}
                </dd>
            </div>
        </div>
    </div>

    {{-- Billing Address --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-credit-card class="w-5 h-5 mr-2" />
            Billing Address
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @php $billing = $record->billing_address ?? []; @endphp
            <div>
                <dt class="text-sm font-medium text-gray-500">Name</dt>
                <dd class="text-sm text-gray-900">{{ ($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? '') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="text-sm text-gray-900">{{ $billing['email'] ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                <dd class="text-sm text-gray-900">{{ $billing['phone'] ?? 'N/A' }}</dd>
            </div>
            <div class="md:col-span-3">
                <dt class="text-sm font-medium text-gray-500">Company</dt>
                <dd class="text-sm text-gray-900">{{ $billing['company'] ?? 'N/A' }}</dd>
            </div>
            <div class="md:col-span-3">
                <dt class="text-sm font-medium text-gray-500">Address</dt>
                <dd class="text-sm text-gray-900">
                    {{ $billing['address_line_1'] ?? '' }}<br>
                    @if(!empty($billing['address_line_2']))
                        {{ $billing['address_line_2'] }}<br>
                    @endif
                    {{ ($billing['city'] ?? '') . ', ' . ($billing['state'] ?? '') . ' ' . ($billing['postal_code'] ?? '') }}<br>
                    {{ $billing['country'] ?? '' }}
                </dd>
            </div>
        </div>
    </div>

    {{-- Payment Information --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-credit-card class="w-5 h-5 mr-2" />
            Payment Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Payment Status</dt>
                <dd>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($record->payment_status === 'pending') bg-primary-100 text-primary-800
                        @elseif($record->payment_status === 'paid') bg-success-100 text-success-800
                        @elseif($record->payment_status === 'failed') bg-danger-100 text-danger-800
                        @elseif($record->payment_status === 'refunded') bg-warning-100 text-warning-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($record->payment_status) }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                <dd class="text-sm text-gray-900">{{ $record->payment_method ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Payment Intent ID</dt>
                <dd class="text-sm text-gray-900 font-mono">{{ $record->payment_intent_id ?? 'N/A' }}</dd>
            </div>
        </div>
    </div>

    {{-- Shipping Information --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-truck class="w-5 h-5 mr-2" />
            Shipping Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Shipping Method</dt>
                <dd class="text-sm text-gray-900">{{ $record->shipping_method ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Tracking Number</dt>
                <dd class="text-sm text-gray-900 font-mono">{{ $record->tracking ?? 'N/A' }}</dd>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-shopping-bag class="w-5 h-5 mr-2" />
            Order Items
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($record->lines as $line)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $line->product_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $line->sku }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($line->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $line->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${{ number_format($line->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Order Summary --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-calculator class="w-5 h-5 mr-2" />
            Order Summary
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Subtotal</dt>
                <dd class="text-lg text-gray-900">${{ number_format($record->subtotal, 2) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Total Discount</dt>
                <dd class="text-lg text-red-600">-${{ number_format($record->total_discount, 2) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Total</dt>
                <dd class="text-xl font-bold text-green-600">${{ number_format($record->total, 2) }}</dd>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    @if($record->notes)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 mr-2" />
            Order Notes
        </h3>
        <div class="prose prose-sm max-w-none">
            {!! nl2br(e($record->notes)) !!}
        </div>
    </div>
    @endif

    {{-- Order History Timeline --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-clock class="w-5 h-5 mr-2" />
            Order History Timeline
        </h3>
        <ul class="relative border-l border-gray-200">
            @foreach($record->histories()->latest()->get() as $history)
            <li class="mb-8 ml-6">
                <span class="absolute -left-3 flex items-center justify-center w-6 h-6 bg-primary-100 rounded-full ring-8 ring-white">
                    @php
                        $icon = match($history->event) {
                            'created' => 'heroicon-o-plus-circle text-green-500',
                            'status_changed' => 'heroicon-o-arrow-path text-blue-500',
                            'shipping_method_changed' => 'heroicon-o-truck text-yellow-500',
                            default => 'heroicon-o-information-circle text-gray-400',
                        };
                    @endphp
                    <x-dynamic-component :component="$icon" class="w-4 h-4" />
                </span>
                <div class="flex items-center mb-1">
                    <span class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $history->event)) }}</span>
                    <span class="ml-2 text-xs text-gray-500">{{ $history->created_at->format('M d, Y g:i A') }}</span>
                </div>
                <div class="text-gray-600 text-sm">{{ $history->description }}</div>
                @if($history->event === 'status_changed')
                    <div class="mt-1 text-xs">
                        <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">From: <strong>{{ $history->old_value['status'] ?? '' }}</strong></span>
                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded ml-2">To: <strong>{{ $history->new_value['status'] ?? '' }}</strong></span>
                    </div>
                @elseif($history->event === 'shipping_method_changed')
                    <div class="mt-1 text-xs">
                        <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">From: <strong>{{ $history->old_value['shipping_method'] ?? '' }}</strong></span>
                        <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded ml-2">To: <strong>{{ $history->new_value['shipping_method'] ?? '' }}</strong></span>
                    </div>
                @endif
            </li>
            @endforeach
        </ul>
    </div>

    {{-- Order History / Event Log --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <x-heroicon-o-clock class="w-5 h-5 mr-2" />
            Order History
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Old Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Value</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($record->histories()->latest()->get() as $history)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $history->event)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $history->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 font-mono">@json($history->old_value)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-900 font-mono">@json($history->new_value)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">{{ $history->created_at->format('M j, Y g:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex justify-end space-x-3 pt-4">
        <a href="{{ route('filament.admin.resources.orders.edit', $record) }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <x-heroicon-o-pencil class="w-4 h-4 mr-2" />
            Edit Order
        </a>
        <a href="{{ route('orders.print-invoice', $record) }}" 
           target="_blank"
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <x-heroicon-o-printer class="w-4 h-4 mr-2" />
            Print Invoice
        </a>
        <a href="{{ route('orders.print-shipping-label', $record) }}" 
           target="_blank"
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <x-heroicon-o-printer class="w-4 h-4 mr-2" />
            Print Shipping Label
        </a>
    </div>
</div> 