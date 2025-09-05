<x-filament-panels::page>
    {{-- Status Filters Section --}}
    <div class="mb-6">
        @include('filament.resources.order-resource.partials.status-filters')
    </div>

    {{-- Table --}}
    {{ $this->table }}
</x-filament-panels::page> 