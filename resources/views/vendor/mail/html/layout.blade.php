<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? setting('store.name', config('app.name')) }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
</head>
<body style="background: #fff; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background: #fff;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px; margin:40px auto; background:#fff; border-radius:18px; box-shadow:0 4px 32px rgba(155,139,122,0.10); border:1px solid #E0E0E0;">
                    <tr>
                        <td style="padding:0 0 0 0;">
                            @isset($header)
                                {{ $header }}
                            @endisset
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px;">
                            {{ $slot }}
                        </td>
                    </tr>
                    @isset($subcopy)
                        <tr>
                            <td style="padding:0 32px;">
                                {{ $subcopy }}
                            </td>
                        </tr>
                    @endisset
                    <tr>
                        <td style="padding:0 32px;">
                            @isset($footer)
                                {{ $footer }}
                            @endisset
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
