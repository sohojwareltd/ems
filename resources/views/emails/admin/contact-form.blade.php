<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Contact Form Submission</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: 'Segoe UI', sans-serif; background-color: #f0f4f8; margin: 0; padding: 0;">
    <div style="background-color: #ffffff; max-width: 600px; margin: 30px auto; padding: 30px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);">
        <h2 style="background-color: #9B8B7A; color: #FAF9F7; padding: 15px 20px; border-radius: 8px; margin-top: 0; font-size: 22px;">
            📬 Contact Form Submission
        </h2>

        <div style="margin: 15px 0; font-size: 16px; color: #333;"><strong style="display: inline-block; min-width: 150px; color: #222;">First Name:</strong> {{ $data['first_name'] }}</div>
        <div style="margin: 15px 0; font-size: 16px; color: #333;"><strong style="display: inline-block; min-width: 150px; color: #222;">Last Name:</strong> {{ $data['last_name'] }}</div>
        <div style="margin: 15px 0; font-size: 16px; color: #333;"><strong style="display: inline-block; min-width: 150px; color: #222;">Email:</strong> {{ $data['email'] }}</div>
        <div style="margin: 15px 0; font-size: 16px; color: #333;"><strong style="display: inline-block; min-width: 150px; color: #222;">Phone:</strong> {{ $data['phone'] ?? 'N/A' }}</div>
        <div style="margin: 15px 0; font-size: 16px; color: #333;"><strong style="display: inline-block; min-width: 150px; color: #222;">Subject:</strong> {{ $data['subject'] }}</div>
        <div style="margin: 15px 0; font-size: 16px; color: #333;"><strong style="display: inline-block; min-width: 150px; color: #222;">Message:</strong><br><span style="display: block; margin-left: 150px;">{!! nl2br(e($data['message'])) !!}</span></div>
        <div style="margin: 15px 0; font-size: 16px; color: #104a6b;"><strong style="display: inline-block; min-width: 150px; color: #222;">Subscribed to Newsletter:</strong> {{ $data['newsletter'] ? 'Yes' : 'No' }}</div>

        <div style="margin-top: 30px; font-size: 14px; color: #777; text-align: center;">
            This message was submitted via your website contact form.
        </div>
    </div>
</body>
</html>
