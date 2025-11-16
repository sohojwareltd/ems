# Payment Credentials Configuration Update

## Overview
This document outlines the changes made to handle payment credentials exclusively through the `.env` file instead of the StoreSettings admin panel.

## Changes Made

### 1. **CheckoutController.php** ✅
- **Line 83**: Uses `env('STRIPE_SECRET')` directly
- **Line 121** (in blade view): Uses `env('STRIPE_KEY')` directly
- No changes needed - already using env()

### 2. **SubscriptionController.php** ✅
**Updated:**
- **Line 47**: Changed from `setting('payments.stripe_secret', env('STRIPE_SECRET'))` → `env('STRIPE_SECRET')`
- **Line 60**: Changed from `setting('payments.stripe_secret', env('STRIPE_SECRET'))` → `env('STRIPE_SECRET')`

### 3. **PayPalController.php** ✅
**Updated:**
- **__construct()**: Changed PayPal credentials from settings to env:
  - `setting('payments.paypal_client_id', env('PAYPAL_CLIENT_ID'))` → `env('PAYPAL_CLIENT_ID')`
  - `setting('payments.paypal_secret', env('PAYPAL_CLIENT_SECRET'))` → `env('PAYPAL_CLIENT_SECRET')`
  - `setting('payments.paypal_sandbox', false)` → `env('PAYPAL_SANDBOX', false)`

### 4. **CheckoutService.php** ✅
**Updated:**
- **initializePaymentGateways()**: 
  - Removed `setting('payments.enable_stripe')` check
  - Removed `setting('payments.enable_paypal')` check
  - Changed PayPal credentials from settings to env:
    - `setting('payments.paypal_client_id', env('PAYPAL_CLIENT_ID'))` → `env('PAYPAL_CLIENT_ID')`
    - `setting('payments.paypal_secret', env('PAYPAL_CLIENT_SECRET'))` → `env('PAYPAL_CLIENT_SECRET')`
    - `setting('payments.paypal_sandbox', false)` → `env('PAYPAL_SANDBOX', false)`

- **getPaymentMethods()**:
  - Stripe: Uses `env('STRIPE_SECRET')` to check if enabled
  - PayPal: Uses `env('PAYPAL_CLIENT_ID')` and `env('PAYPAL_CLIENT_SECRET')` to check if enabled

### 4. **resources/views/frontend/pages/subscriptions/payment.blade.php** ✅
**Updated:**
- **Line 412**: Changed from `setting('payments.stripe_key', env('STRIPE_KEY'))` → `env('STRIPE_KEY')`

### 5. **resources/views/frontend/checkout/payment.blade.php** ✅
- **Line 121**: Already using `env('STRIPE_KEY')` - No changes needed

### 6. **ChargeDefaultSubscriptions.php** ✅
- **Line 29**: Already using `env('STRIPE_SECRET')` - No changes needed

### 7. **SettingsSeeder.php** ✅
**Updated:**
- Removed entire 'payments' settings array to avoid confusion
- Added comment explaining credentials are now in .env only

### 8. **StoreSettings.php** (Admin Panel)
**Note:** The payment credentials fields are still visible in the admin panel but are set to `->visible(false)` or `->visible(true)` based on the code. You may want to completely hide the Payments tab or remove it.

## Required .ENV Variables

Add these to your `.env` file:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here

# PayPal Configuration (Optional)
PAYPAL_CLIENT_ID=your_paypal_client_id_here
PAYPAL_CLIENT_SECRET=your_paypal_client_secret_here
PAYPAL_SANDBOX=true

# For Production, use:
# STRIPE_KEY=pk_live_your_publishable_key_here
# STRIPE_SECRET=sk_live_your_secret_key_here
# PAYPAL_SANDBOX=false
```

## Payment Detection Logic

### Stripe
- **Enabled when**: `env('STRIPE_SECRET')` is set and not empty
- **Used in**:
  - Regular checkout (`CheckoutController@payment`)
  - Subscription payments (`SubscriptionController@subscriptionsPayment`)
  - Payment processing

### PayPal
- **Enabled when**: Both `env('PAYPAL_CLIENT_ID')` AND `env('PAYPAL_CLIENT_SECRET')` are set
- **Used in**:
  - Payment method selection
  - PayPal service initialization

## Testing Checklist

- [ ] Test regular product checkout with Stripe
- [ ] Test subscription payment with Stripe
- [ ] Test PayPal checkout (if configured)
- [ ] Verify payment methods show/hide based on .env configuration
- [ ] Test with empty credentials (should gracefully disable payment methods)
- [ ] Verify existing orders still work
- [ ] Test subscription renewals

## Benefits of This Approach

1. **Security**: Credentials stay in .env (not in database)
2. **Simplicity**: Single source of truth for payment configuration
3. **Version Control**: .env.example can document required variables
4. **Deployment**: Easier to manage across environments (dev/staging/production)
5. **Standard Laravel Practice**: Follows Laravel conventions

## Migration Notes

If you have existing payment credentials in the database settings:
1. Copy them to your `.env` file
2. Test all payment flows
3. Optionally, remove the payment settings from the database:
   ```sql
   DELETE FROM settings WHERE `key` LIKE 'payments.%';
   ```

## Admin Panel Update Recommendation

Consider updating `StoreSettings.php`:

1. **Option 1**: Completely remove the Payments tab
   ```php
   // Remove or comment out the entire Tabs\Tab::make('Payments') section
   ```

2. **Option 2**: Keep the tab but make it read-only with instructions
   ```php
   Tabs\Tab::make('Payments')
       ->schema([
           Section::make('Payment Configuration')
               ->description('Payment credentials are now managed via the .env file. Please contact your system administrator.')
               ->schema([
                   Placeholder::make('info')
                       ->content('Add STRIPE_KEY, STRIPE_SECRET, etc. to your .env file.')
               ])
       ])
   ```

## Files Modified

1. ✅ `app/Http/Controllers/SubscriptionController.php`
2. ✅ `app/Http/Controllers/PayPalController.php`
3. ✅ `app/Services/CheckoutService.php`
4. ✅ `resources/views/frontend/pages/subscriptions/payment.blade.php`
5. ✅ `database/seeders/SettingsSeeder.php`

## Files Already Correct

1. ✅ `app/Http/Controllers/CheckoutController.php`
2. ✅ `resources/views/frontend/checkout/payment.blade.php`
3. ✅ `app/Console/Commands/ChargeDefaultSubscriptions.php`

---

**Last Updated**: November 16, 2025
**Status**: ✅ Complete - All payment flows now use .env exclusively
