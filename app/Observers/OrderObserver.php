<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Services\OrderEmailService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    public function created(Order $order)
    {
        OrderHistory::create([
            'order_id' => $order->id,
            'event' => 'created',
            'description' => 'Order was created.',
            'old_value' => null,
            'new_value' => $order->toArray(),
        ]);
    }

    public function updated(Order $order)
    {
        $orderEmailService = app()->make(OrderEmailService::class);

        // Status change
        $originalStatus = $order->getOriginal('status');
        if ($originalStatus !== $order->status) {
            $orderEmailService->sendOrderStatusUpdate($order, $originalStatus, $order->status);
            OrderHistory::create([
                'order_id' => $order->id,
                'event' => 'status_changed',
                'old_value' => ['status' => $originalStatus],
                'new_value' => ['status' => $order->status],
                'description' => "Order status changed from $originalStatus to {$order->status}.",
            ]);

            // Grant audiobook access if order is now paid or completed
            // Note: Digital products are handled immediately after payment in CheckoutService and PayPalController
            // This is a fallback for orders that might be updated through admin panel
            if (in_array($order->status, ['paid', 'completed', 'confirmed']) && $order->user) {
                $this->grantAudiobookAccessIfNeeded($order);
            }
        }

        // Shipping method change
        $originalShipping = $order->getOriginal('shipping_method');
        if ($originalShipping !== $order->shipping_method && !empty($order->shipping_method)) {
            $orderEmailService->sendShippingConfirmation($order);
            OrderHistory::create([
                'order_id' => $order->id,
                'event' => 'shipping_method_changed',
                'old_value' => ['shipping_method' => $originalShipping],
                'new_value' => ['shipping_method' => $order->shipping_method],
                'description' => "Shipping method changed from $originalShipping to {$order->shipping_method}.",
            ]);
        }
    }

    /**
     * Grant audiobook access if needed (fallback method)
     * 
     * @param Order $order
     */
    protected function grantAudiobookAccessIfNeeded($order)
    {
        try {
            $user = $order->user;
            $grantedAudiobooks = [];

            foreach ($order->lines as $line) {
                $product = $line->product;
                if (!$product) continue;

                // Only process digital products
                if (!$product->isDigital()) continue;

                // Get all audiobooks associated with this product
                $audioBooks = $product->audioBooks()->get();
                
                foreach ($audioBooks as $audioBook) {
                    // Check if user already has access
                    $existingAccess = $user->audioBooks()
                        ->where('audio_book_id', $audioBook->id)
                        ->first();

                    if (!$existingAccess) {
                        // Grant access
                        $user->audioBooks()->attach($audioBook->id, [
                            'unlocked_at' => now()
                        ]);
                        
                        $grantedAudiobooks[] = [
                            'product_name' => $product->name,
                            'audiobook_title' => $audioBook->title
                        ];

                        Log::info('Audiobook access granted via OrderObserver fallback', [
                            'user_id' => $user->id,
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'audiobook_id' => $audioBook->id,
                            'audiobook_title' => $audioBook->title
                        ]);
                    }
                }
            }

            if (count($grantedAudiobooks) > 0) {
                Log::info('Audiobook access granted via OrderObserver fallback', [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'granted_audiobooks' => $grantedAudiobooks
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to grant audiobook access via OrderObserver fallback', [
                'order_id' => $order->id,
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage()
            ]);
        }
    }
} 