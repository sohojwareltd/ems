<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiry Reply</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #f5f8f2 50%, #ffffff 100%);
        }
        .header {
            background: linear-gradient(135deg, #19390b, #0d1f06);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dbe5d5;
        }
        .reply-section {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #19390b;
            margin: 20px 0;
        }
        .original-enquiry {
            background-color: #f5f8f2;
            padding: 15px;
            border-left: 4px solid #dbe5d5;
            margin: 20px 0;
        }
        .original-enquiry h3 {
            margin-top: 0;
            color: #19390b;
        }
        .detail-row {
            margin: 10px 0;
        }
        .detail-label {
            font-weight: bold;
            color: #19390b;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #647067;
            font-size: 12px;
            border-top: 1px solid #dbe5d5;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Response to Your Enquiry</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $enquiry->first_name }} {{ $enquiry->last_name }},</p>
        
        <p>Thank you for contacting us. Here is our response to your enquiry:</p>
        
        <div class="reply-section">
            {!! nl2br(e($replyMessage)) !!}
        </div>
        
        <div class="original-enquiry">
            <h3>Your Original Enquiry</h3>
            
            <div class="detail-row">
                <span class="detail-label">Date:</span> {{ $enquiry->created_at->format('d M Y H:i') }}
            </div>
            
            @if($enquiry->category)
            <div class="detail-row">
                <span class="detail-label">Category:</span> {{ $enquiry->category->name }}
            </div>
            @endif
            
            <div class="detail-row">
                <span class="detail-label">Your Message:</span>
            </div>
            <div style="margin-top: 10px; white-space: pre-wrap;">{{ $enquiry->message }}</div>
        </div>
        
        <p>If you have any further questions, please don't hesitate to contact us again.</p>
        
        <p>Best regards,<br>
        <strong>The Support Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated response to your enquiry. Please do not reply directly to this email.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
