<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify Password Change - {{ setting('store.name', config('app.name')) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #19390b;
            --primary-dark: #0d1f06;
            --accent-bg: #f5f8f2;
            --text-light: #647067;
            --border-color: #dbe5d5;
        }
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, #f8f9ff 0%, #f5f8f2 50%, #ffffff 100%);
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 12px 36px rgba(15, 23, 42, 0.14);
            border: 1px solid var(--border-color);
            padding: 40px 32px;
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
            padding: 18px 20px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(25, 57, 11, 0.08), rgba(13, 31, 6, 0.04));
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
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 0.5em;
        }
        .divider {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
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
            color: #1f2937;
        }
        .button {
            text-align: center;
            margin: 32px 0;
        }
        .button a {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 12px 34px;
            text-decoration: none;
            border-radius: 40px;
            display: inline-block;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
            transition: all 0.25s ease;
        }
        .button a:hover {
            opacity: 0.9;
            transform: translateY(-1px);
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
            <div class="title">Verify Your Password Change</div>
        </div>

        <div class="divider"></div>

        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>

            <p>You have requested to change your password. Please confirm this request using the button below:</p>

            <div class="highlight">
                This verification link will expire in <strong>1 hour</strong> for security reasons.
            </div>
        </div>

        <div class="button">
            <a href="{{ $verifyUrl }}">Verify Password Change</a>
        </div>

        <div class="content">
            <p><b>Important:</b> If you did not request this change, please ignore this email. Your password will remain unchanged.</p>
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
