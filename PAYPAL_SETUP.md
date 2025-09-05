# PayPal Payment Integration Setup

This guide will help you set up PayPal payments for your e-commerce application.

## 1. PayPal Developer Account Setup

1. Create a PayPal Developer account at [https://developer.paypal.com](https://developer.paypal.com)
2. Log in to the PayPal Developer Dashboard
3. Navigate to "My Apps & Credentials"
4. Create a new app for your application

## 2. Environment Configuration

Add the following environment variables to your `.env` file:

```env
# PayPal Configuration
PAYPAL_CLIENT_ID=your_paypal_client_id_here
PAYPAL_CLIENT_SECRET=your_paypal_client_secret_here
PAYPAL_MODE=sandbox  # Use 'live' for production
PAYPAL_WEBHOOK_ID=your_webhook_id_here  # Optional for webhooks
```

## 3. PayPal SDK Installation

The PayPal PHP SDK is already included in your `composer.json`:

```json
"paypal/rest-api-sdk-php": "^1.6"
```

If you need to install it manually:

```bash
composer require paypal/rest-api-sdk-php
```

## 4. Configuration Files

### Services Configuration (`config/services.php`)

The PayPal configuration has been added to your services config:

```php
'paypal' => [
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    'mode' => env('PAYPAL_MODE', 'sandbox'), // 'sandbox' or 'live'
    'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
],
```

## 5. Features Implemented

### Backend Services
- ✅ `PayPalService` - Core PayPal integration service
- ✅ `PayPalController` - Handles PayPal callbacks and webhooks
- ✅ Updated `CheckoutService` - Integrated PayPal payment processing
- ✅ PayPal routes for success, cancel, and webhook handling

### Frontend Integration
- ✅ PayPal payment method option in checkout
- ✅ PayPal branding and information display
- ✅ Redirect flow to PayPal for payment approval
- ✅ Success and cancel handling

### Payment Flow
1. **Order Creation**: Order is created with pending status
2. **PayPal Payment Creation**: PayPal payment is created via API
3. **User Redirect**: User is redirected to PayPal for approval
4. **Payment Execution**: Payment is executed when user returns
5. **Order Confirmation**: Order is updated and confirmation emails sent

## 6. Testing

### Sandbox Testing
1. Use PayPal Sandbox accounts for testing
2. Create sandbox buyer and seller accounts in PayPal Developer Dashboard
3. Test the complete payment flow

### Test Accounts
You can create test accounts in the PayPal Developer Dashboard:
- Buyer accounts for testing payments
- Seller accounts for receiving payments

## 7. Production Setup

For production:

1. **Switch to Live Mode**:
   ```env
   PAYPAL_MODE=live
   ```

2. **Update Credentials**:
   - Replace sandbox credentials with live credentials
   - Update client ID and secret for live environment

3. **Webhook Configuration**:
   - Set up webhooks in PayPal Developer Dashboard
   - Configure webhook URL: `https://yourdomain.com/paypal/webhook`
   - Add webhook ID to environment variables

4. **SSL Certificate**:
   - Ensure your site has a valid SSL certificate
   - PayPal requires HTTPS for all transactions

## 8. Webhook Events

The following webhook events are handled:

- `PAYMENT.CAPTURE.COMPLETED` - Payment completed
- `PAYMENT.CAPTURE.DENIED` - Payment denied
- `PAYMENT.CAPTURE.REFUNDED` - Payment refunded

## 9. Error Handling

The integration includes comprehensive error handling:

- Payment creation failures
- Payment execution failures
- Network timeouts
- Invalid credentials
- Webhook verification failures

## 10. Security Features

- ✅ Secure API communication
- ✅ Payment data never stored locally
- ✅ Webhook signature verification (can be implemented)
- ✅ Session-based payment tracking
- ✅ Comprehensive logging

## 11. API Endpoints

### PayPal Routes
- `GET /paypal/success` - Handle successful payments
- `GET /paypal/cancel` - Handle cancelled payments
- `POST /paypal/webhook` - Handle webhook notifications

### Checkout Integration
- PayPal payment method option in checkout form
- Automatic redirect to PayPal for payment approval
- Seamless return to order confirmation

## 12. Troubleshooting

### Common Issues

1. **"PayPal is not configured"**
   - Check your environment variables
   - Ensure client ID and secret are correct
   - Verify PayPal mode (sandbox/live)

2. **"Payment creation failed"**
   - Check PayPal API credentials
   - Verify order data format
   - Check PayPal account status

3. **"Payment execution failed"**
   - Verify payment ID and payer ID
   - Check if payment was already executed
   - Ensure PayPal account has sufficient funds

4. **Webhook not receiving events**
   - Verify webhook URL is accessible
   - Check webhook configuration in PayPal Dashboard
   - Ensure webhook events are properly configured

### Debug Mode

Enable debug mode in your `.env` file to see detailed error messages:

```env
APP_DEBUG=true
```

PayPal logs will be written to `storage/logs/paypal.log` when debug is enabled.

## 13. Additional Features

Future enhancements could include:

- PayPal Express Checkout
- PayPal Credit integration
- Subscription payments
- Refund processing
- Multiple currency support
- PayPal Smart Payment Buttons
- PayPal One Touch

## 14. Support

For PayPal-specific issues, refer to:
- [PayPal Developer Documentation](https://developer.paypal.com/docs/)
- [PayPal PHP SDK](https://github.com/paypal/PayPal-PHP-SDK)
- [PayPal Support](https://www.paypal.com/support/)

## 15. Testing Checklist

Before going live, ensure you've tested:

- [ ] Sandbox payment creation
- [ ] Sandbox payment execution
- [ ] Payment cancellation flow
- [ ] Webhook event handling
- [ ] Error scenarios
- [ ] Order status updates
- [ ] Email notifications
- [ ] Session management
- [ ] Security measures

## 16. Production Checklist

Before going live, ensure you have:

- [ ] Live PayPal credentials configured
- [ ] SSL certificate installed
- [ ] Webhooks configured for live environment
- [ ] Error monitoring set up
- [ ] Logging configured
- [ ] Support procedures in place
- [ ] Backup payment methods available 