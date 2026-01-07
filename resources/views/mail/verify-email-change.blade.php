<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, var(--primary-color, #3b82f6), var(--primary-dark, #2563eb)); color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .button { display: inline-block; padding: 12px 30px; background: #3b82f6; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { padding: 20px; text-align: center; color: #6b7280; font-size: 14px; }
        .alert { background: #fef3c7; padding: 15px; border-left: 4px solid #f59e0b; margin: 20px 0; }
        .email-box { background: #e0f2fe; padding: 15px; border-radius: 5px; margin: 15px 0; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Your Email Change</h1>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>You have requested to change your email address to:</p>
            
            <div class="email-box">
                <strong style="font-size: 18px; color: #0369a1;">{{ $new_email }}</strong>
            </div>
            
            <p>To confirm this change, please click the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $verifyUrl }}" class="button">Verify Email Change</a>
            </div>
            
            <p>Or copy and paste this link into your browser:</p>
            <p style="word-break: break-all; color: #3b82f6;">{{ $verifyUrl }}</p>
            
            <div class="alert">
                <strong>⚠️ Important:</strong> This link will expire in <strong>24 hours</strong> for security reasons.
            </div>
            
            <p><strong>If you did not request this change</strong>, please ignore this email. Your email will remain unchanged.</p>
        </div>
        <div class="footer">
            <p>Thanks,<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
