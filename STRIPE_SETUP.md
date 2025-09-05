# Stripe Payment Integration Setup

This guide will help you set up Stripe payments for your e-commerce application.

## 1. Stripe Account Setup

1. Create a Stripe account at [https://stripe.com](https://stripe.com)
2. Get your API keys from the Stripe Dashboard
3. For testing, use the test keys (they start with `pk_test_` and `sk_test_`)

## 2. Environment Configuration

Add the following environment variables to your `.env` file:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here
```

## 3. Test Card Numbers

For testing payments, you can use these test card numbers:

- **Visa**: `4242424242424242`
- **Visa (debit)**: `4000056655665556`
- **Mastercard**: `5555555555554444`
- **American Express**: `378282246310005`

Use any future expiry date (e.g., `12/25`) and any 3-digit CVC.

## 4. Features Implemented

### Frontend (Checkout Page)
- ✅ Stripe Elements integration for secure card input
- ✅ Real-time card validation
- ✅ PaymentMethod creation (modern Stripe API)
- ✅ AJAX form submission
- ✅ Error handling and user feedback
- ✅ Toast notifications

### Backend (CheckoutService)
- ✅ Stripe PHP SDK integration
- ✅ Payment intent creation with PaymentMethods
- ✅ Payment processing
- ✅ Error handling
- ✅ Order creation with payment info

## 5. How It Works

1. **Frontend**: User enters card details using Stripe Elements
2. **PaymentMethod Creation**: Stripe.js creates a PaymentMethod (modern approach)
3. **Form Submission**: PaymentMethod ID is sent to backend via AJAX
4. **Payment Processing**: Backend creates payment intent with PaymentMethod
5. **Order Creation**: Order is created with payment information
6. **Confirmation**: User is redirected to confirmation page

## 6. Security Features

- ✅ Card data never touches your server
- ✅ PCI compliance through Stripe Elements
- ✅ Secure PaymentMethod-based payments
- ✅ Server-side payment validation

## 7. Testing

1. Add items to cart
2. Go to checkout page
3. Fill in billing/shipping information
4. Select "Credit Card" payment method
5. Enter test card details
6. Submit order
7. Check order confirmation

## 8. Production Setup

For production:

1. Replace test keys with live keys
2. Set up webhooks for payment status updates
3. Configure proper error handling
4. Test thoroughly with small amounts
5. Ensure SSL is enabled

## 9. Troubleshooting

### Common Issues:

1. **"Stripe is not configured"**
   - Check your environment variables
   - Ensure keys are correct

2. **"Card declined"**
   - Use valid test card numbers
   - Check card expiry date

3. **"Invalid payment method"**
   - Ensure PaymentMethod is being created
   - Check Stripe.js integration

4. **"A token may not be passed in as a PaymentMethod"**
   - This error occurs when using old token-based API
   - The integration now uses PaymentMethods (modern approach)

### Debug Mode

Enable debug mode in your `.env` file to see detailed error messages:

```env
APP_DEBUG=true
```

## 10. API Changes

### From Tokens to PaymentMethods

The integration uses Stripe's modern PaymentMethod API instead of the deprecated token-based approach:

- **Old (Tokens)**: `stripe.createToken(cardElement)`
- **New (PaymentMethods)**: `stripe.createPaymentMethod({type: 'card', card: cardElement})`

This provides better security and more features.

## 11. Additional Features

Future enhancements could include:
- Saved payment methods
- Subscription payments
- Multiple currencies
- Apple Pay / Google Pay
- Payment method switching
- Refund processing

## 12. Support

For Stripe-specific issues, refer to:
- [Stripe Documentation](https://stripe.com/docs)
- [Stripe PHP SDK](https://github.com/stripe/stripe-php)
- [Stripe Support](https://support.stripe.com) 