<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <x-heroicon-o-clock class="w-5 h-5 mr-2" />
        Order History Timeline
    </h3>
    <ul class="relative border-l border-gray-200">
        @foreach($this->getHistories() as $history)
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