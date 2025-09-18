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
use Stripe\SetupIntent;

class SubscriptionController extends Controller
{
    public function index()
    {
        // Fetch available subscription plans from the database
        // $plans = Plan::where('active', true)->get();
        $months = plan::where('interval', 'month')->where('active', true)->get();
        $years = plan::where('interval', 'year')->where('active', true)->get();

        return view('frontend.pages.subscriptions.index', compact('months', 'years'));
    }
    public function subscriptions(Request $request)
    {
        $subscriptions = Subscription::where('user_id', Auth::id())->with('plan')->orderBy('created_at', 'desc')->get();

        return view('user.subscriptions', compact('subscriptions'));
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

        Stripe::setApiKey( setting('payments.stripe_secret', env('STRIPE_SECRET')));


        return view('frontend.pages.subscriptions.payment', [
            'plan' => $plan,
            'clientSecret' => auth()->user()->createSetupIntent()->client_secret,
        ]);
    }


    public function subscribe(Request $request, Plan $plan)
    {
        $user = auth()->user();
        Stripe::setApiKey( setting('payments.stripe_secret', env('STRIPE_SECRET')));

        // 1. Setup customer & payment method
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($request->payment_method);

        $trialDays = $plan->trial_period_days;
        $startsAt = now();
        $trialStartsAt = $startsAt;
        $trialEndsAt = $trialDays > 0 ? $startsAt->copy()->addDays($trialDays) : null;
        $endsAt = match ($plan->interval) {
            'month' => ($trialEndsAt ?? $startsAt)->copy()->addMonth(),
            'year' => ($trialEndsAt ?? $startsAt)->copy()->addYear(),
            default => ($trialEndsAt ?? $startsAt),
        };

        $paymentIntent = null;
        $paymentMethod = PaymentMethod::retrieve($request->payment_method);

        if ($trialDays > 0) {
            $setupIntent = SetupIntent::create([
                'customer' => $user->stripe_id,
                'payment_method' => $request->payment_method,
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);
            $stripe_id = $setupIntent->id;
            $stripe_status = $setupIntent->status;
        } else {
            // 2b. Immediate charge
            $paymentIntent = PaymentIntent::create([
                'amount' => $plan->price * 100,
                'currency' => 'usd',
                'customer' => $user->stripe_id,
                'payment_method' => $request->payment_method,
                'off_session' => true,
                'confirm' => true,
            ]);

            $stripe_id = $paymentIntent->id;
            $stripe_status = $paymentIntent->status;
        }

        Subscription::where('user_id', $user->id)
            ->where('type', 'default')
            ->update(['type' => null]);

        // 3. Create local Subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'stripe_id' => $stripe_id,
            'stripe_status' => $stripe_status,
            'amount' => $plan->price,
            'status' => $trialDays > 0 ? 'trialing' : 'active',
            'starts_at' => $startsAt,
            'trial_starts_at' => $startsAt,
            'trial_ends_at' => $trialEndsAt,
            'ends_at' => $endsAt,
            'type' => 'default',
        ]);

        // 4. Save payment only if PaymentIntent used
        if ($paymentIntent) {
            Payment::create([
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? null,
                'amount' => $plan->price,
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
        }

        return redirect()->route('dashboard')->with('success', 'Subscription created successfully!');
    }

    public function cancel(Subscription $subscription)
    {
        $user = Auth::user();

        // Ensure this subscription belongs to the user
        if ($subscription->user_id !== $user->id) {
            abort(403);
        }

        // Update status and end date
        $subscription->update([
            'status' => 'cancelled',
            'canceled_at' => now(),
        ]);

        return back()->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Set a subscription as default.
     */
    public function setDefault(Subscription $subscription)
    {
        $user = Auth::user();

        // Ensure this subscription belongs to the user
        if ($subscription->user_id !== $user->id) {
            abort(403);
        }

        // Reset previous default
        $user->subscriptions()->update(['type' => false]);

        // Set current as default
        $subscription->update(['is_default' => true]);

        return back()->with('success', 'Default subscription updated successfully.');
    }
}
