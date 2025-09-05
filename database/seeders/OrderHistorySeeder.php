<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class OrderHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusSteps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded', 'completed'];
        $shippingMethods = ['FedEx', 'UPS', 'USPS', 'DHL', 'Standard Shipping', 'Express Shipping'];

        foreach (Order::all() as $order) {
            $createdAt = $order->created_at;
            // Created event
            OrderHistory::create([
                'order_id' => $order->id,
                'event' => 'created',
                'old_value' => null,
                'new_value' => $order->toArray(),
                'description' => 'Order was created.',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Simulate 1-3 status changes
            $currentStatus = $order->status;
            $statusTrail = Arr::random(array_diff($statusSteps, [$currentStatus]), rand(1, 3));
            if (!is_array($statusTrail)) $statusTrail = [$statusTrail];
            $lastStatus = 'pending';
            $stepTime = $createdAt->copy();
            foreach ($statusTrail as $status) {
                $stepTime = $stepTime->addHours(rand(2, 24));
                OrderHistory::create([
                    'order_id' => $order->id,
                    'event' => 'status_changed',
                    'old_value' => ['status' => $lastStatus],
                    'new_value' => ['status' => $status],
                    'description' => "Order status changed from $lastStatus to $status.",
                    'created_at' => $stepTime,
                    'updated_at' => $stepTime,
                ]);
                $lastStatus = $status;
            }

            // Shipping method change (if order has shipping_method)
            if ($order->shipping_method) {
                $shipTime = $createdAt->copy()->addHours(rand(1, 48));
                $oldMethod = Arr::random($shippingMethods);
                OrderHistory::create([
                    'order_id' => $order->id,
                    'event' => 'shipping_method_changed',
                    'old_value' => ['shipping_method' => $oldMethod],
                    'new_value' => ['shipping_method' => $order->shipping_method],
                    'description' => "Shipping method changed from $oldMethod to {$order->shipping_method}.",
                    'created_at' => $shipTime,
                    'updated_at' => $shipTime,
                ]);
            }
        }
    }
} 