<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

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

        // Demo gateway subscription ID
        $fakeGatewaySubscriptionId = 'SUB-' . strtoupper(uniqid());

        // Save subscription
        Subscription::create([
            'user_id' => Auth::id() ?? 3, // Demo user
            'plan_id' => $plan->id,
            // 'gateway' => $request->gateway, 
            // 'gateway_subscription_id' => $fakeGatewaySubscriptionId,
            'status' => 'active',
            'trial_ends_at' => $plan->trial_period_days
                ? now()->addDays($plan->trial_period_days)
                : null,
            'starts_at' => now(),
            'ends_at' => $plan->interval === 'monthly'
                ? now()->addMonth()
                : now()->addYear(),
        ]);

        // 1️⃣ Save Payment record (to payments table)
        // $payment = Payment::create([
        //     'user_id' => auth()->id() ?? 1,
        //     'plan_id' => $plan->id,
        //     'gateway' => $request->gateway,
        //     'cardholder' => $request->cardholder,
        //     'cardnumber' => $request->cardnumber,
        //     'expiry' => $request->expiry,
        //     'cvv' => $request->cvv,
        //     'amount' => $plan->price,
        //     'currency' => $plan->currency,
        //     'status' => 'completed', // demo
        // ]);
        return ('Subscription completed successfully!');
        // return redirect()->route('subscriptions.success')
        //     ->with('success', 'Subscription completed successfully!')
        //     ->with('plan_name', $plan->name);
    }


}
