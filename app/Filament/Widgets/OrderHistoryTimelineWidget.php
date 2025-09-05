<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Filament\Pages\Page;
use Illuminate\Support\Arr;

class OrderHistoryTimelineWidget extends Widget
{
    protected static string $view = 'filament.widgets.order-history-timeline-widget';
    public ?Model $record = null;

    public static function canView(): bool
    {
        // Show on any order-related page
        return request()->routeIs('filament.admin.resources.orders.*') || 
               request()->routeIs('filament.resources.orders.*');
    }

    public function mount()
    {
        $this->record = $this->getRecord();
    }

    public function getRecord()
    {
        // Try multiple ways to get the record
        if ($this->record) {
            return $this->record;
        }
        
        // Try to get from route parameter
        $recordId = request()->route('record');
        if ($recordId) {
            return \App\Models\Order::find($recordId);
        }
        
        // Try to get from livewire properties
        if (method_exists($this, 'getRecord')) {
            return parent::getRecord();
        }
        
        return null;
    }

    public function getHistories()
    {
        $order = $this->getRecord();
        if (!$order) return collect();
        return $order->histories()->latest()->get();
    }
} 