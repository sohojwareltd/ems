
@dd('heello');
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Email - Test</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #00b22d 0%, #019c26 100%); padding: 30px; text-align: center;">
            <h1 style="color: white; margin: 0; font-size: 28px;">EMS</h1>
            <p style="color: white; margin: 10px 0 0 0; font-size: 14px;">Email Verification</p>
        </div>
        
        <!-- Content -->
        <div style="padding: 40px 30px;">
            <h2 style="color: #333; margin-top: 0;">Hello {{ $user->name }}!</h2>
            
            <p style="font-size: 16px; margin: 20px 0;">
                Thank you for registering with <strong>EMS</strong>. We're excited to have you on board!
            </p>
            
            <p style="font-size: 16px; margin: 20px 0;">
                To complete your registration, please verify your email address by clicking the button below:
            </p>
            
            <!-- Button -->
            <div style="text-align: center; margin: 40px 0;">
                <a href="{{ $url }}" style="background-color: #00b22d; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 16px; font-weight: bold;">
                    Verify Email Address
                </a>
            </div>
            
            <p style="font-size: 14px; color: #666; margin: 30px 0 10px 0;">
                <strong>Note:</strong> This link will expire in 60 minutes.
            </p>
            
            <p style="font-size: 14px; color: #666;">
                If you did not create an account, no further action is required.
            </p>
            
            <hr style="margin: 30px 0; border: none; border-top: 1px solid #e0e0e0;">
            
            <p style="font-size: 12px; color: #999; margin: 10px 0;">
                <strong>Having trouble clicking the button?</strong><br>
                Copy and paste the URL below into your web browser:
            </p>
            <p style="word-break: break-all; font-size: 11px; color: #666; background-color: #f9f9f9; padding: 10px; border-radius: 5px;">
                {{ $url }}
            </p>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #f9f9f9; padding: 20px; text-align: center; border-top: 1px solid #e0e0e0;">
            <p style="font-size: 12px; color: #666; margin: 5px 0;">
                This is an automated email. Please do not reply to this message.
            </p>
            <p style="font-size: 12px; color: #999; margin: 5px 0;">
                &copy; {{ date('Y') }} EMS. All rights reserved.
            </p>
        </div>
        
    </div>
</body>
</html>
