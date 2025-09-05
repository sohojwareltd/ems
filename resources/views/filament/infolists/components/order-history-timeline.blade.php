<div class="py-4">
    @if($getState()->count() > 0)
        <ul class="relative border-l-2 border-gray-200 pl-8">
            @foreach($getState() as $history)
            <li class="mb-10 flex items-start group">
                <!-- Timeline Icon -->
                <span class="absolute left-0 flex items-center justify-center w-8 h-8 bg-white border-2 border-primary-200 rounded-full shadow-sm -translate-x-1/2 z-10">
                    @php
                        [$icon, $color] = match($history->event) {
                            'created' => ['heroicon-o-plus-circle', 'text-green-500'],
                            'status_changed' => ['heroicon-o-arrow-path', 'text-blue-500'],
                            'shipping_method_changed' => ['heroicon-o-truck', 'text-yellow-500'],
                            default => ['heroicon-o-information-circle', 'text-gray-400'],
                        };
                    @endphp
                    <x-dynamic-component :component="$icon" class="w-5 h-5 {{ $color }}" />
                </span>
                <!-- Timeline Content -->
                <div  style="margin-left: 30px;margin-bottom: 10px;" class=" flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold text-base text-gray-900">{{ ucfirst(str_replace('_', ' ', $history->event)) }}</span>
                        <span class="text-xs text-gray-500">{{ $history->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="text-gray-600 text-sm mb-2">{{ $history->description }}</div>
                    @if($history->event === 'status_changed')
                        <div class="flex flex-wrap gap-2 mb-1">
                            <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-800 text-xs font-medium">
                                From: <span class="ml-1 font-semibold">{{ ucfirst($history->old_value['status'] ?? 'Unknown') }}</span>
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded bg-blue-100 text-blue-800 text-xs font-medium">
                                To: <span class="ml-1 font-semibold">{{ ucfirst($history->new_value['status'] ?? 'Unknown') }}</span>
                            </span>
                        </div>
                    @elseif($history->event === 'shipping_method_changed')
                        <div class="flex flex-wrap gap-2 mb-1">
                            <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-800 text-xs font-medium">
                                From: <span class="ml-1 font-semibold">{{ $history->old_value['shipping_method'] ?? 'Unknown' }}</span>
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-xs font-medium">
                                To: <span class="ml-1 font-semibold">{{ $history->new_value['shipping_method'] ?? 'Unknown' }}</span>
                            </span>
                        </div>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    @else
        <div class="text-center py-8 text-gray-500">
            <x-heroicon-o-clock class="w-12 h-12 mx-auto mb-4 text-gray-300" />
            <p class="text-sm">No order history available</p>
        </div>
    @endif
</div> 