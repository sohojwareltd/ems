<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use Carbon\Carbon;
class SubscriptionController extends Controller
{
    public function index()
    {
        // Fetch available subscription plans from the database
        $plans = Plan::where('active', true)->get();
        $months = plan::where('interval', 'monthly')->where('active', true)->get();
        $years = plan::where('interval', 'yearly')->where('active', true)->get();

        return view('frontend.pages.subscriptions.index', compact('plans', 'months', 'years'));
    }

    public function subscriptionsPayment($id)
    {
        $plan = Plan::findOrFail($id);

        return view('frontend.pages.subscriptions.payment', compact('plan'));
    }


    public function paymentMethod(Request $request, $planId)
    {
        $plan = Plan::findOrFail($planId);

      
        $userId = Auth::id() ?? 3; // Demo user fallback

        // Trial / start / end dates set করি
        $now = Carbon::now();
        $trialStartsAt = $now;
        $trialEndsAt = $now->copy()->addDays(7); // Example: 7 days trial
        $startsAt = $trialEndsAt;
        $endsAt = $trialEndsAt->copy()->addMonth(); // Example: 1 month plan

        // Subscription create
        $subscription = Subscription::create([
            'user_id' => $userId,
            'plan_id' => $plan->id,
            'status' => 'active',
            'amount' => $plan->price,
            'currency' => 'usd',
            'trial_starts_at' => $trialStartsAt,
            'trial_ends_at' => $trialEndsAt,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'canceled_at' => null,
            'metadata' => json_encode([
                'gateway' => $request->gateway,
                'cardholder' => $request->cardholder,
            ]),
        ]);

        // Fake stripe IDs (demo purpose)
        $fakeIntentId = 'pi_' . uniqid();
        $fakeChargeId = 'ch_' . uniqid();

        // Payment create
        Payment::create([
            'subscription_id' => $subscription->id,
            'user_id' => $userId,
            'stripe_payment_intent_id' => $fakeIntentId,
            'stripe_charge_id' => $fakeChargeId,
            'amount' => $plan->price,
            'currency' => 'usd',
            // 'status' => 'active',
            // 'type' => $request->gateway,
            'paid_at' => Carbon::now(),
            'failed_at' => null,
            'failure_reason' => null,
            'metadata' => json_encode([
                'cardholder' => $request->cardholder,
                'last4' => substr($request->cardnumber, -4),
                'expiry' => $request->expiry,
            ]),
        ]);
        return('Subscription and Payment successfully created!');
        // return redirect()->route('plans.index')->with('success', 'Subscription and Payment successfully created!');
    }


}
