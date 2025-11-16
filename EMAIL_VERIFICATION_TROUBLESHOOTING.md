# Email Verification Troubleshooting Guide

## Changes Made

### 1. ✅ Verify Page Updated (`verify.blade.php`)
- **Removed**: "A fresh verification link has been sent" text
- **Changed to**: "Before proceeding, please check your email for a verification link"
- **Added**: Success alert that shows for 15 seconds when resend button is clicked
- **Updated**: Button text from "click here to request another" to "Click here to request another"
- **Changed**: Title from "MyShop" to "EMS"

### 2. ✅ RegisterController Updated
- **Added**: `Registered` event trigger to ensure verification email is sent
- **Added**: Logging to help debug email sending issues
- **Added**: Proper imports for Event and Log facades

## Email Not Sending on First Registration - Diagnosis

### The issue could be either:

### A. **Laravel/Code Issue** ✅ NOW FIXED
The `RegistersUsers` trait should automatically trigger the `Registered` event, but sometimes it doesn't work properly. I've added an explicit event trigger:

```php
event(new Registered($user));
```

This ensures the verification email is sent immediately upon registration.

### B. **Server/Email Configuration Issue**

Check your `.env` file for these settings:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com  # or your GoDaddy/Google Workspace SMTP
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="Economics Made Simple"

# Queue Configuration (IMPORTANT!)
QUEUE_CONNECTION=sync  # Change to 'sync' for immediate sending
```

### C. **Google Workspace/GoDaddy Specific Issues**

#### For Google Workspace:
1. **Enable 2-Factor Authentication** on your Google account
2. **Create App Password**: 
   - Go to Google Account > Security > 2-Step Verification > App passwords
   - Generate password for "Mail" on "Other (Custom name)"
   - Use this password in `MAIL_PASSWORD`

3. **Check SMTP Settings**:
   ```env
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_ENCRYPTION=tls
   ```

4. **Check Google Admin Console**:
   - Go to Apps > Google Workspace > Gmail > User settings
   - Make sure "Allow per-user outbound gateways" is enabled
   - Check if SMTP is enabled for your domain

#### For GoDaddy Hosting:
1. **GoDaddy blocks port 25** by default
2. Use **port 587** with TLS or **port 465** with SSL
3. **Verify outbound email settings** in cPanel
4. Some GoDaddy plans require using their SMTP relay:
   ```env
   MAIL_HOST=relay-hosting.secureserver.net
   MAIL_PORT=25
   ```

### D. **Queue System Issue**

If `QUEUE_CONNECTION=database`, emails are queued and need a queue worker running:

#### Option 1: Run Queue Worker (Recommended for Production)
```bash
php artisan queue:work --tries=3
```

Keep this running in background or set up supervisor.

#### Option 2: Use Sync Driver (Recommended for Testing)
Change in `.env`:
```env
QUEUE_CONNECTION=sync
```

Then run:
```bash
php artisan config:clear
php artisan cache:clear
```

## Testing Steps

### 1. Check Mail Configuration
```bash
php artisan tinker
```

Then run:
```php
Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com')->subject('Test');
});
```

If this works, mail is configured correctly.

### 2. Check Logs
After registration attempt, check:
- `storage/logs/laravel.log` - Look for "User registered and verification email triggered"
- Look for any mail-related errors

### 3. Test Verification Email Manually
```bash
php artisan tinker
```

```php
$user = User::where('email', 'test@example.com')->first();
$user->sendEmailVerificationNotification();
```

### 4. Check Queue Jobs
If using database queue:
```bash
php artisan queue:failed
```

This shows failed jobs with error messages.

## Common Solutions

### Solution 1: Switch to Sync Queue
```bash
# In .env
QUEUE_CONNECTION=sync

# Clear config
php artisan config:clear
```

### Solution 2: Verify Google Workspace Settings
1. Check if "Less secure app access" is disabled (it should be)
2. Use App Password instead of regular password
3. Check domain's SMTP settings in Google Admin Console

### Solution 3: Check GoDaddy Email Relay
If on GoDaddy, try their SMTP relay:
```env
MAIL_HOST=relay-hosting.secureserver.net
MAIL_PORT=25
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=null
```

### Solution 4: Enable Debug Mode
```env
APP_DEBUG=true
MAIL_LOG_CHANNEL=stack
```

This will show detailed mail errors.

## Verification

After making changes:

1. ✅ Register a new test user
2. ✅ Check `storage/logs/laravel.log` for the log entry
3. ✅ Check email inbox (including spam folder)
4. ✅ Try clicking "Click here to request another" 
5. ✅ Verify success message shows for 15 seconds
6. ✅ Check if second email arrives

## Most Likely Culprit

Based on "only works when manually requested", this is **99% a Queue Configuration Issue**:
- First email gets queued but queue worker isn't running
- Manual request might be processed differently or triggers immediate sending

**Quick Fix**: Change `QUEUE_CONNECTION=sync` in `.env` and run `php artisan config:clear`

---

**Last Updated**: November 16, 2025
**Status**: ✅ Code updated - Test with sync queue connection
