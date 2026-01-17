<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify Email Change - {{ setting('store.name', config('app.name')) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #19390b;
            --accent-bg: #f5f5f5;
            --text-light: #666;
            --border-color: #E0E0E0;
        }
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #faf9f7;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 32px rgba(25,57,11,0.10);
            border: 1px solid var(--border-color);
            padding: 40px 32px;
        }
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        .header .brand {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .header .title {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: #2E2E2E;
            font-weight: 700;
            margin-bottom: 0.5em;
        }
        .divider {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), #2d5a15);
            border-radius: 2px;
            margin: 32px auto 24px auto;
        }
        .content {
            color: #4A3F35;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .content p {
            margin-bottom: 16px;
        }
        .highlight {
            background: var(--accent-bg);
            border-left: 4px solid var(--primary);
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 24px;
            color: #4A3F35;
        }
        .button {
            text-align: center;
            margin: 32px 0;
        }
        .button a {
            background: var(--primary);
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            font-weight: 500;
            transition: opacity 0.3s;
        }
        .button a:hover {
            opacity: 0.9;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 24px;
            color: #856404;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
            color: var(--text-light);
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">{{ setting('store.name', config('app.name')) }}</div>
            <div class="title">Verify Your Email Change</div>
        </div>

        <div class="divider"></div>

        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>You have requested to change your email address to:</p>
            
            <div class="highlight">
                <strong>{{ $new_email }}</strong>
            </div>

            <p>To confirm this email change, please click the button below:</p>
        </div>

        <div class="button">
            <a href="{{ $verifyUrl }}">Verify Email Change</a>
        </div>

        <div class="content">
            <p>This verification link will expire in 24 hours for security reasons.</p>
        </div>

        <div class="content">
            <p><b>Important:</b> If you did not request this change, please ignore this email. Your email will remain unchanged.</p>
        </div>

        <div class="footer">
            <p>
                Thanks,<br>
                The {{ setting('store.name', config('app.name')) }} Team
            </p>
        </div>
    </div>
</body>
</html>
