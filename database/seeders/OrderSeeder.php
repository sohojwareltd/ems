<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();
        
        // Define all available statuses
        $statuses = [
            'pending',
            'confirmed', 
            'processing',
            'shipped',
            'delivered',
            'returned',
            'refunded',
            'cancelled',
            'completed'
        ];
        
        // Define payment methods
        $paymentMethods = ['stripe', 'paypal', 'cod'];
        
        // Define payment statuses
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];
        
        // Define shipping methods
        $shippingMethods = ['FedEx', 'UPS', 'USPS', 'DHL', 'Standard Shipping', 'Express Shipping'];
        
        // Define cities and countries for variety
        $cities = [
            ['city' => 'New York', 'country' => 'US'],
            ['city' => 'Los Angeles', 'country' => 'US'],
            ['city' => 'Chicago', 'country' => 'US'],
            ['city' => 'Houston', 'country' => 'US'],
            ['city' => 'Phoenix', 'country' => 'US'],
            ['city' => 'Philadelphia', 'country' => 'US'],
            ['city' => 'San Antonio', 'country' => 'US'],
            ['city' => 'San Diego', 'country' => 'US'],
            ['city' => 'Dallas', 'country' => 'US'],
            ['city' => 'San Jose', 'country' => 'US'],
            ['city' => 'Toronto', 'country' => 'CA'],
            ['city' => 'Vancouver', 'country' => 'CA'],
            ['city' => 'Montreal', 'country' => 'CA'],
            ['city' => 'London', 'country' => 'GB'],
            ['city' => 'Manchester', 'country' => 'GB'],
            ['city' => 'Birmingham', 'country' => 'GB'],
            ['city' => 'Paris', 'country' => 'FR'],
            ['city' => 'Lyon', 'country' => 'FR'],
            ['city' => 'Berlin', 'country' => 'DE'],
            ['city' => 'Munich', 'country' => 'DE'],
        ];
        
        // Create 500 orders
        for ($i = 0; $i < 500; $i++) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            $shippingMethod = $shippingMethods[array_rand($shippingMethods)];
            $location = $cities[array_rand($cities)];
            
            // Generate a random created_at date within the past year
            $createdAt = now()->subDays(rand(0, 364))->setTime(rand(0,23), rand(0,59), rand(0,59));
            
            // Generate realistic tracking number based on shipping method
            $tracking = null;
            if (in_array($status, ['shipped', 'delivered', 'returned', 'completed'])) {
                $tracking = match($shippingMethod) {
                    'FedEx' => 'FDX' . rand(100000000, 999999999),
                    'UPS' => 'UPS' . rand(100000000, 999999999),
                    'USPS' => 'USPS' . rand(100000000, 999999999),
                    'DHL' => 'DHL' . rand(100000000, 999999999),
                    default => 'TRK' . rand(100000000, 999999999),
                };
            }
            
            // Adjust payment status based on order status for realism
            if (in_array($status, ['cancelled', 'returned'])) {
                $paymentStatus = 'refunded';
            } elseif (in_array($status, ['delivered', 'completed'])) {
                $paymentStatus = 'paid';
            } elseif ($status === 'pending') {
                $paymentStatus = 'pending';
            }
        $first_name = fake()->firstName();
        $last_name = fake()->lastName();
        $email = fake()->email();
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'status' => $status,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'payment_intent_id' => $paymentStatus === 'paid' ? 'pi_' . uniqid() : null,
                'total' => 0,
                'currency' => 'USD',
                'shipping_address' => [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => fake()->phoneNumber(),
                    'address' => rand(100, 9999) . ' ' . ['Main St', 'Oak Ave', 'Pine Rd', 'Elm St', 'Maple Dr'][array_rand(['Main St', 'Oak Ave', 'Pine Rd', 'Elm St', 'Maple Dr'])],
                    'city' => $location['city'],
                    'state' => ['NY', 'CA', 'TX', 'FL', 'IL', 'PA', 'OH', 'GA', 'NC', 'MI'][array_rand(['NY', 'CA', 'TX', 'FL', 'IL', 'PA', 'OH', 'GA', 'NC', 'MI'])],
                    'country' => $location['country'],
                    'zip' => rand(10000, 99999),
                ],
                'billing_address' => [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => fake()->phoneNumber(),
                    'address' => rand(100, 9999) . ' ' . ['Main St', 'Oak Ave', 'Pine Rd', 'Elm St', 'Maple Dr'][array_rand(['Main St', 'Oak Ave', 'Pine Rd', 'Elm St', 'Maple Dr'])],
                    'city' => $location['city'],
                    'state' => ['NY', 'CA', 'TX', 'FL', 'IL', 'PA', 'OH', 'GA', 'NC', 'MI'][array_rand(['NY', 'CA', 'TX', 'FL', 'IL', 'PA', 'OH', 'GA', 'NC', 'MI'])],
                    'country' => $location['country'],
                    'zip' => rand(10000, 99999),
                ],
                'notes' => rand(1, 10) === 1 ? ['Gift wrap requested', 'Leave at front door', 'Call before delivery', 'Fragile items', 'Rush delivery'][array_rand(['Gift wrap requested', 'Leave at front door', 'Call before delivery', 'Fragile items', 'Rush delivery'])] : null,
                'shipping_method' => $shippingMethod,
                'tracking' => $tracking,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
            
            $orderTotal = 0;
            $numProducts = rand(1, 5);
            
            foreach ($products->random($numProducts) as $product) {
                $qty = rand(1, 5);
                // Simulate a variant if product has variants
                $variant = null;
                if (!empty($product->variants)) {
                    $variant = $product->variants[array_rand($product->variants)];
                }
                
                $price = $product->price ?? rand(10, 200);
                $lineTotal = $price * $qty;
                
                $line = OrderLine::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $price,
                    'quantity' => $qty,
                    'total' => $lineTotal,
                    'variant' => $variant,
                    'notes' => rand(1, 20) === 1 ? 'Special request' : null,
                
                ]);
                $orderTotal += $lineTotal;
            }
            
            $order->update(['total' => $orderTotal]);
        }
    }
} 