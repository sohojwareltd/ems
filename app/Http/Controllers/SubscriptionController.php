<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use Carbon\Carbon;
use Stripe\PaymentIntent;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Stripe\PaymentMethod;

class SubscriptionController extends Controller
{
    public function index()
    {
        // Fetch available subscription plans from the database
        $plans = Plan::where('active', true)->get();
        $months = plan::where('interval', 'monthly')->where('active', true)->get();
        $years = plan::where('interval', 'yearly')->where('active', true)->get();

        return view('frontend.pages.subscriptions.index', compact('months', 'years'));
    }

    public function getSetupIntent()
    {
        return response()->json([
            'clientSecret' => auth()->user()->createSetupIntent()->client_secret,
        ]);
    }

    public function subscriptionsPayment($id)
    {
        $plan = Plan::findOrFail($id);
        $user = Auth::user();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $plan->price * 100, // price in cents
            'currency' => 'eur',
            'automatic_payment_methods' => ['enabled' => true],
            'metadata' => [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
            ],
        ]);

        return view('frontend.pages.subscriptions.payment', [
            'plan' => $plan,
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }



    public function subscribe(Request $request, Plan $plan)
    {
        $user = auth()->user();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 1. Create Product & Price
        $product = Product::create(['name' => $plan->name]);

        $price = Price::create([
            'unit_amount' => $plan->price * 100,
            'currency' => 'usd',
            'recurring' => ['interval' => $plan->interval],
            'product' => $product->id,
        ]);

        // 2. Attach payment method
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($request->payment_method);

        // 3. Create Stripe subscription
        $subscription = $user->newSubscription('default', $price->id);

        if ($plan->trial_period_days > 0) {
            $subscription->trialDays($plan->trial_period_days);
        }

        $stripeSubscription = $subscription->create($request->payment_method);

        // 4. Calculate dates
        $startsAt = now();
        $trialEndsAt = $stripeSubscription->trial_end
            ? Carbon::createFromTimestamp($stripeSubscription->trial_end)
            : null;

        $endsAt = match ($plan->interval) {
            'month' => $trialEndsAt ?? $startsAt->copy()->addMonth(),
            'year' => $trialEndsAt ?? $startsAt->copy()->addYear(),
            default => $trialEndsAt,
        };


        $pm = PaymentMethod::retrieve($request->payment_method);

        Payment::create([
            'subscription_id' => $stripeSubscription->id,
            'user_id' => $user->id,
            'stripe_payment_intent_id' => $stripeSubscription->latest_invoice->payment_intent ?? null,
            'stripe_charge_id' => null,
            'amount' => $plan->price,
            'currency' => 'usd',
            'paid_at' => Carbon::now(),
            'failed_at' => null,
            'status' => 'succeeded',
            'failure_reason' => null,
            'metadata' => json_encode([
                'cardholder' => $pm->billing_details->name ?? null,
                'last4' => $pm->card->last4 ?? null,
                'brand' => $pm->card->brand ?? null,
                'expiry_month' => $pm->card->exp_month ?? null,
                'expiry_year' => $pm->card->exp_year ?? null,
            ]),
        ]);
        auth()->user()->update(['plan_id' => $plan->id]);


        return redirect()->route('dashboard')->with('success', 'Subscription and payment recorded successfully!');
    }
}
