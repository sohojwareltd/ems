<?php

namespace App\Console\Commands;

use App\Enums\SubscriptionStatus;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class ChargeDefaultSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:charge-default';
    protected $description = 'Charge all users default subscription if trial ended or subscription expired';
    /**
     * Execute the console command.
     */

    public function handle()
    {
        Stripe::setApiKey(  env('STRIPE_SECRET'));
        $users = User::whereHas('subscriptions', function ($q) {
            $q->where('type', 'default')
                ->whereIn('status', [SubscriptionStatus::TRIALING->value, SubscriptionStatus::ACTIVE->value]);
        })->get();

        foreach ($users as $user) {
            $subscription = $user->subscriptions()->where('type', 'default')->first();

            if (!$subscription) continue;

            $needsCharge = false;

            // Check trial ended
            if (
                $subscription->status->value === SubscriptionStatus::TRIALING->value
                && $subscription->trial_ends_at
                && $subscription->trial_ends_at->isPast()
            ) {
                $needsCharge = true;
            }

            // Check active subscription ended
            if (
                $subscription->status->value === SubscriptionStatus::ACTIVE->value
                && $subscription->ends_at
                && $subscription->ends_at->isPast()
            ) {
                $needsCharge = true;
            }

            if (!$needsCharge) continue;

            try {
                // Ensure customer exists in Stripe
                if (!$user->stripe_id) {
                    $user->createOrGetStripeCustomer();
                }

                // Use default payment method
                $paymentMethod = $user->defaultPaymentMethod();
                if (!$paymentMethod) {
                    $this->error("User {$user->id} has no payment method. Skipping.");
                    continue;
                }

                // Create PaymentIntent
                $paymentIntent = PaymentIntent::create([
                    'amount' => $subscription->amount * 100,
                    'currency' => 'usd',
                    'customer' => $user->stripe_id,
                    'payment_method' => $paymentMethod->id,
                    'off_session' => true,
                    'confirm' => true,
                ]);

                // Save Payment
                Payment::create([
                    'subscription_id' => $subscription->id,
                    'user_id' => $user->id,
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? null,
                    'amount' => $subscription->amount,
                    'currency' => 'usd',
                    'paid_at' => Carbon::now(),
                    'failed_at' => null,
                    'status' => $paymentIntent->status,
                    'failure_reason' => null,
                    'metadata' => json_encode([
                        'cardholder' => $paymentMethod->billing_details->name ?? null,
                        'last4' => $paymentMethod->card->last4 ?? null,
                        'brand' => $paymentMethod->card->brand ?? null,
                        'expiry_month' => $paymentMethod->card->exp_month ?? null,
                        'expiry_year' => $paymentMethod->card->exp_year ?? null,
                    ]),
                ]);

                // Update subscription if trial ended
                if ($subscription->status->value === SubscriptionStatus::TRIALING->value) {
                    $subscription->update([
                        'status' => SubscriptionStatus::ACTIVE->value,
                        'starts_at' => now(),
                        'ends_at' => match ($subscription->plan->interval) {
                            'month' => now()->addMonth(),
                            'year' => now()->addYear(),
                            default => now()->addMonth(),
                        },
                        'trial_ends_at' => null,
                        'metadata' => json_encode([
                            'cardholder' => $paymentMethod->billing_details->name ?? null,
                            'last4' => $paymentMethod->card->last4 ?? null,
                            'brand' => $paymentMethod->card->brand ?? null,
                            'expiry_month' => $paymentMethod->card->exp_month ?? null,
                            'expiry_year' => $paymentMethod->card->exp_year ?? null,
                        ]),
                    ]);
                }
                // Renew active subscription
                elseif ($subscription->status->value === SubscriptionStatus::ACTIVE->value) {
                    Subscription::create([
                        'user_id' => $user->id,
                        'plan_id' => $subscription->plan_id,
                        'stripe_id' => $subscription->stripe_id,
                        'stripe_status' => $paymentIntent->status,
                        'amount' => $subscription->amount,
                        'status' => SubscriptionStatus::ACTIVE->value,
                        'starts_at' => now(),
                        'ends_at' => match ($subscription->plan->interval) {
                            'month' => now()->addMonth(),
                            'year' => now()->addYear(),
                            default => now()->addMonth(),
                        },
                        'type' => 'default',
                        'metadata' => json_encode([
                            'cardholder' => $paymentMethod->billing_details->name ?? null,
                            'last4' => $paymentMethod->card->last4 ?? null,
                            'brand' => $paymentMethod->card->brand ?? null,
                            'expiry_month' => $paymentMethod->card->exp_month ?? null,
                            'expiry_year' => $paymentMethod->card->exp_year ?? null,
                        ]),
                    ]);

                    // Make old subscription non-default
                    $subscription->update(['type' => null]);
                }

                $this->info("User {$user->id} subscription charged successfully.");
            } catch (\Exception $e) {
                \Log::error("Failed to charge subscription ID {$subscription->id}: " . $e->getMessage());
                $this->error("Failed to charge subscription ID {$subscription->id}");
            }
        }

        $this->info('Default subscriptions charging completed.');
    }
}
