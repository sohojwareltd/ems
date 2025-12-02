<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #00b22d;">Verify Your Email Address</h2>
        
        <p>Hello {{ $user->name }},</p>
        
        <p>Thank you for registering with EMS. Please click the button below to verify your email address:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #00b22d; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Verify Email Address
            </a>
        </div>
        
        <p>If you did not create an account, no further action is required.</p>
        
        <p>If you're having trouble clicking the button, copy and paste the URL below into your web browser:</p>
        <p style="word-break: break-all; color: #666;">{{ $url }}</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">
        
        <p style="font-size: 12px; color: #666;">
            This is an automated email. Please do not reply to this message.
        </p>
    </div>
</body>
</html>
