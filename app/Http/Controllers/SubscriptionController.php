<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\PaymentMethod;
use Stripe\SetupIntent;

class SubscriptionController extends Controller
{
    public function index()
    {
        // Fetch available subscription plans from the database
        // $plans = Plan::where('active', true)->get();
        $plans = Plan::where('active', true)
            ->where('is_hide', false)
            ->get();

        return view('frontend.pages.subscriptions.index', compact('plans'));
    }
    public function subscriptions(Request $request)
    {
        $subscriptions = Subscription::where('user_id', Auth::id())->with('plan')->orderBy('created_at', 'desc')->get();

        return view('user.subscriptions', compact('subscriptions'));
    }

    public function showClaimAccessCode()
    {
        return view('user.claim-access-code', [
            'hasValidSubscription' => $this->userHasValidSubscription($this->currentUser()->id),
        ]);
    }

    public function searchClaimAccessCode(Request $request)
    {
        if ($this->userHasValidSubscription($this->currentUser()->id)) {
            return redirect()
                ->route('user.subscription')
                ->with('error', 'You already have an active subscription. You cannot claim a new one now.');
        }

        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:255'],
        ]);

        $couponCode = trim((string) $validated['coupon_code']);

        $plan = Plan::query()
            ->where('active', true)
            ->where('is_coupon_enabled', true)
            ->whereRaw('UPPER(coupon_code) = ?', [strtoupper($couponCode)])
            ->first();

        if (! $plan) {
            return back()
                ->withInput()
                ->with('error', 'No plan found for this access code.');
        }

        $validation = $plan->validateSubscriptionCoupon($couponCode);

        if (! $validation['valid']) {
            return back()
                ->withInput()
                ->with('error', $validation['message']);
        }

        return view('user.claim-access-code', [
            'matchedPlan' => $plan,
            'couponCode' => $validation['code'],
            'hasValidSubscription' => false,
        ]);
    }

    public function claimAccessCode(Request $request)
    {
        if ($this->userHasValidSubscription($this->currentUser()->id)) {
            return redirect()
                ->route('user.subscription')
                ->with('error', 'You already have an active subscription. You cannot claim a new one now.');
        }

        $validated = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'coupon_code' => ['required', 'string', 'max:255'],
        ]);

        $plan = Plan::query()
            ->whereKey($validated['plan_id'])
            ->where('active', true)
            ->where('is_coupon_enabled', true)
            ->first();

        if (! $plan) {
            return back()->with('error', 'The selected plan is not available for coupon access.');
        }

        $couponCode = trim((string) $validated['coupon_code']);

        try {
            $this->createCouponSubscription($plan, $this->currentUser(), $couponCode);

            return redirect()->route('user.subscription')->with('success', 'Access claimed successfully.');
        } catch (ValidationException $exception) {
            return back()
                ->withInput()
                ->withErrors($exception->errors())
                ->with('error', $exception->errors()['coupon_code'][0] ?? 'Invalid access code.');
        }
    }

    public function getSetupIntent()
    {
        $user = $this->currentUser();

        return response()->json([
            'clientSecret' => $user->createSetupIntent()->client_secret,
        ]);
    }

    public function subscriptionsPayment($id)
    {
        $plan = Plan::findOrFail($id);
        $clientSecret = null;

        if (filled(env('STRIPE_SECRET')) && filled(env('STRIPE_KEY'))) {
            $user = $this->currentUser();
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $clientSecret = $user->createSetupIntent()->client_secret;
        }

        return view('frontend.pages.subscriptions.payment', [
            'plan' => $plan,
            'clientSecret' => $clientSecret,
        ]);
    }


    public function subscribe(Request $request, Plan $plan)
    {
        $user = $this->currentUser();
        $validated = $request->validate([
            'coupon_code' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string'],
        ]);

        $couponCode = trim((string) ($validated['coupon_code'] ?? ''));

        if ($couponCode !== '') {
            try {
                $subscription = $this->createCouponSubscription($plan, $user, $couponCode);

                return redirect()->route('dashboard')->with('success', 'Subscription activated successfully with coupon access.');
            } catch (ValidationException $exception) {
                return back()
                    ->withInput()
                    ->withErrors($exception->errors())
                    ->with('error', $exception->errors()['coupon_code'][0] ?? 'Invalid coupon code.');
            }
        }

        if (blank($validated['payment_method'] ?? null)) {
            return back()
                ->withInput()
                ->with('error', 'Payment details are required unless you use a valid coupon code.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        // 1. Setup customer & payment method
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($request->payment_method);

        $trialDays = $plan->trial_period_days;
        $startsAt = now();
        $trialStartsAt = $startsAt;
        $trialEndsAt = $trialDays > 0 ? $startsAt->copy()->addDays($trialDays) : null;
        $endsAt = $plan->calculateStandardEndDate($trialEndsAt ?? $startsAt);

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
                'type' => 'subscription',
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
        Subscription::where('user_id', $user->id)->update(['type' => null]);

        // Set current as default
        $subscription->update(['type' => 'default']);

        return back()->with('success', 'Default subscription updated successfully.');
    }

    protected function currentUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    protected function userHasValidSubscription(int $userId): bool
    {
        return Subscription::query()
            ->where('user_id', $userId)
            ->whereIn('status', ['active', 'trialing'])
            ->get()
            ->contains(fn (Subscription $subscription): bool => $subscription->isValid() && ! $subscription->hasEnded());
    }

    protected function createCouponSubscription(Plan $plan, $user, string $couponCode): Subscription
    {
        return DB::transaction(function () use ($plan, $user, $couponCode) {
            $lockedPlan = Plan::query()->lockForUpdate()->findOrFail($plan->id);
            $validation = $lockedPlan->validateSubscriptionCoupon($couponCode);

            if (! $validation['valid']) {
                throw ValidationException::withMessages([
                    'coupon_code' => $validation['message'],
                ]);
            }

            $startsAt = now();
            $endsAt = $lockedPlan->calculateStandardEndDate($startsAt);

            Subscription::where('user_id', $user->id)
                ->where('type', 'default')
                ->update(['type' => null]);

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $lockedPlan->id,
                'stripe_id' => null,
                'stripe_status' => 'coupon_access',
                'amount' => 0,
                'currency' => $lockedPlan->currency,
                'status' => 'active',
                'starts_at' => $startsAt,
                'trial_starts_at' => null,
                'trial_ends_at' => null,
                'ends_at' => $endsAt,
                'type' => 'default',
                'metadata' => [
                    'coupon_code' => $validation['code'],
                    'coupon_applied_at' => now()->toDateTimeString(),
                    'coupon_max_uses' => $lockedPlan->coupon_max_uses,
                    'coupon_total_used_before' => $lockedPlan->coupon_total_used,
                    'access_mode' => 'coupon',
                ],
            ]);

            $lockedPlan->increment('coupon_total_used');

            return $subscription;
        });
    }
}
