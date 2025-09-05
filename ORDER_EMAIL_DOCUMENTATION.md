# Order Email System Documentation

This document provides comprehensive information about the order email system implemented in your Laravel e-commerce application.

## Overview

The order email system automatically sends emails to customers and administrators at various stages of the order lifecycle. It includes professional email templates, error handling, and logging for all email operations.

## Email Types

### 1. Order Confirmation Email
- **Trigger**: When a new order is successfully placed
- **Recipient**: Customer
- **Template**: `resources/views/emails/orders/confirmation.blade.php`
- **Class**: `App\Mail\OrderConfirmation`

**Features:**
- Order details and summary
- Item list with variants and SKUs
- Billing and shipping addresses
- Order totals breakdown
- Professional styling

### 2. Order Status Update Email
- **Trigger**: When order status changes
- **Recipient**: Customer
- **Template**: `resources/views/emails/orders/status-update.blade.php`
- **Class**: `App\Mail\OrderStatusUpdate`

**Features:**
- Previous and new status information
- Status-specific messages
- Tracking information (if available)
- Shipping method details

### 3. Shipping Confirmation Email
- **Trigger**: When order is marked as shipped
- **Recipient**: Customer
- **Template**: `resources/views/emails/orders/shipping-confirmation.blade.php`
- **Class**: `App\Mail\ShippingConfirmation`

**Features:**
- Tracking number and URL
- Estimated delivery time
- Shipping method information
- Direct tracking links for major carriers (UPS, FedEx, USPS, DHL)

### 4. Order Cancellation Email
- **Trigger**: When order is cancelled
- **Recipient**: Customer
- **Template**: `resources/views/emails/orders/cancellation.blade.php`
- **Class**: `App\Mail\OrderCancellation`

**Features:**
- Cancellation reason
- Refund information based on payment method
- Order details for reference

### 5. Order Refund Email
- **Trigger**: When order is refunded
- **Recipient**: Customer
- **Template**: `resources/views/emails/orders/refund.blade.php`
- **Class**: `App\Mail\OrderRefund`

**Features:**
- Refund amount and reason
- Payment method-specific refund information
- Processing timeline

### 6. New Order Notification Email
- **Trigger**: When a new order is placed
- **Recipient**: Admin
- **Template**: `resources/views/emails/admin/new-order.blade.php`
- **Class**: `App\Mail\NewOrderNotification`

**Features:**
- Complete order summary
- Customer information
- Direct link to admin panel
- Urgent action required notification

## Service Class

### OrderEmailService

The `App\Services\OrderEmailService` class handles all email operations with proper error handling and logging.

#### Key Methods:

```php
// Send individual emails
$emailService->sendOrderConfirmation($order);
$emailService->sendOrderStatusUpdate($order, $previousStatus, $newStatus);
$emailService->sendShippingConfirmation($order);
$emailService->sendOrderCancellation($order, $reason);
$emailService->sendOrderRefund($order, $amount, $reason);
$emailService->sendNewOrderNotification($order);

// Send grouped emails
$emailService->sendNewOrderEmails($order);
$emailService->sendStatusChangeEmails($order, $previousStatus, $newStatus);
$emailService->sendCancellationEmails($order, $reason);
$emailService->sendRefundEmails($order, $amount, $reason);

// Test email functionality
$emailService->testEmail($order, $emailType);
```

## Integration Points

### 1. CheckoutService Integration

The `CheckoutService` automatically sends confirmation emails when orders are placed:

```php
protected function sendConfirmationEmails($order)
{
    try {
        $emailService = new OrderEmailService();
        $results = $emailService->sendNewOrderEmails($order);
        
        Log::info('Order confirmation emails sent', [
            'order_id' => $order->id,
            'results' => $results
        ]);
    } catch (Exception $e) {
        Log::error('Failed to send order confirmation emails', [
            'order_id' => $order->id,
            'error' => $e->getMessage()
        ]);
    }
}
```

### 2. Filament Admin Panel Integration

#### Test Email Action

A test email action is available in the Filament admin panel for testing email functionality:

- **Location**: Order resource actions
- **Action**: `TestOrderEmailAction`
- **Features**:
  - Select email type to test
  - Specify test email address
  - Success/failure notifications
  - Detailed logging

#### Usage in Admin Panel:

1. Navigate to Orders in the admin panel
2. Click the actions menu (three dots) on any order
3. Select "Test Email"
4. Choose email type and test email address
5. Click "Send Test Email"

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourstore.com
MAIL_FROM_NAME="Your Store Name"

# Admin Email for Notifications
MAIL_ADMIN_EMAIL=admin@yourstore.com
```

### Mail Configuration

The mail configuration is in `config/mail.php` and includes:

- Default mailer settings
- Admin email configuration
- Markdown mail settings

## Email Templates

### Template Structure

All email templates are located in `resources/views/emails/`:

```
emails/
├── orders/
│   ├── confirmation.blade.php
│   ├── status-update.blade.php
│   ├── shipping-confirmation.blade.php
│   ├── cancellation.blade.php
│   └── refund.blade.php
└── admin/
    └── new-order.blade.php
```

### Template Features

- **Responsive Design**: All templates are mobile-friendly
- **Professional Styling**: Clean, modern design with proper branding
- **Dynamic Content**: Order-specific information and calculations
- **Error Handling**: Graceful fallbacks for missing data
- **Accessibility**: Proper semantic HTML and alt text

### Customization

To customize email templates:

1. Edit the Blade templates in `resources/views/emails/`
2. Update styling in the `<style>` sections
3. Modify content and layout as needed
4. Test with the admin panel test action

## Error Handling and Logging

### Error Handling

All email operations include comprehensive error handling:

- Try-catch blocks around email sending
- Graceful failure handling
- Detailed error logging
- User-friendly error messages

### Logging

Email operations are logged with detailed information:

```php
Log::info('Order confirmation email sent', [
    'order_id' => $order->id,
    'order_number' => $order->order_number,
    'customer_email' => $order->billing_address['email']
]);

Log::error('Failed to send order confirmation email', [
    'order_id' => $order->id,
    'order_number' => $order->order_number,
    'customer_email' => $order->billing_address['email'],
    'error' => $e->getMessage()
]);
```

## Testing

### Manual Testing

1. **Use the Admin Panel**: Use the test email action in the Filament admin panel
2. **Check Logs**: Monitor Laravel logs for email operations
3. **Email Preview**: Use Laravel's mail preview feature

### Automated Testing

Create tests for email functionality:

```php
public function test_order_confirmation_email_is_sent()
{
    $order = Order::factory()->create();
    $emailService = new OrderEmailService();
    
    $result = $emailService->sendOrderConfirmation($order);
    
    $this->assertTrue($result);
    Mail::assertSent(OrderConfirmation::class);
}
```

## Best Practices

### 1. Email Delivery

- Use a reliable email service (Mailgun, SendGrid, etc.)
- Configure SPF and DKIM records
- Monitor email delivery rates
- Set up bounce handling

### 2. Template Management

- Keep templates simple and focused
- Use consistent branding
- Test on multiple email clients
- Include unsubscribe links where appropriate

### 3. Performance

- Use queues for email sending in production
- Implement rate limiting
- Monitor email service quotas
- Cache email templates when possible

### 4. Security

- Validate all email addresses
- Sanitize order data in templates
- Use HTTPS for all links
- Implement email authentication

## Troubleshooting

### Common Issues

1. **Emails Not Sending**
   - Check mail configuration
   - Verify SMTP credentials
   - Check server logs
   - Test with mail log driver

2. **Template Errors**
   - Check Blade syntax
   - Verify template paths
   - Test with sample data
   - Check for missing variables

3. **Styling Issues**
   - Test in multiple email clients
   - Use inline CSS
   - Avoid complex layouts
   - Test on mobile devices

### Debug Mode

Enable debug mode to see detailed error information:

```php
// In .env
APP_DEBUG=true
MAIL_MAILER=log
```

This will log emails to `storage/logs/laravel.log` instead of sending them.

## Future Enhancements

### Planned Features

1. **Email Templates Management**
   - Admin interface for editing templates
   - Template versioning
   - A/B testing for email templates

2. **Advanced Email Features**
   - Email scheduling
   - Drip campaigns
   - Email analytics
   - Personalization

3. **Integration Improvements**
   - Webhook support
   - Real-time email tracking
   - Advanced reporting
   - Customer communication history

## Support

For issues or questions about the email system:

1. Check the Laravel logs for error details
2. Verify email configuration
3. Test with the admin panel test action
4. Review this documentation
5. Check Laravel Mail documentation

## Changelog

### Version 1.0.0
- Initial implementation of order email system
- Six email types with professional templates
- Admin panel integration
- Comprehensive error handling and logging
- Test email functionality 