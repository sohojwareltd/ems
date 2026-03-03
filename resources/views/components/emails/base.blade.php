@props([
    'title' => '',
])

@php
    $storeName = setting('store.name', config('app.name'));
    $logoPath = setting('store.logo');
    $logoUrl = null;

    if ($logoPath) {
        if (filter_var($logoPath, FILTER_VALIDATE_URL)) {
            $logoUrl = $logoPath;
        } else {
            $normalizedLogoPath = ltrim($logoPath, '/');

            if (str_starts_with($normalizedLogoPath, 'storage/')) {
                $normalizedLogoPath = substr($normalizedLogoPath, 8);
            }

            $logoUrl = url(\Illuminate\Support\Facades\Storage::url($normalizedLogoPath));
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ setting('store.name', config('app.name')) }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f7faf7;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #1f2937;
        }

        .email-shell {
            width: 100%;
            padding: 28px 12px;
            box-sizing: border-box;
        }

        .email-card {
            max-width: 640px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .email-header {
            background: #ffffff;
            color: #1f2937;
            padding: 20px 24px;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }

        .brand {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.2px;
            color: #00a63e;
        }

        .title {
            margin: 6px 0 0;
            font-size: 15px;
            font-weight: 500;
            color: #374151;
        }

        .email-content {
            padding: 24px;
            font-size: 14px;
            line-height: 1.65;
        }

        .email-content p {
            margin: 0 0 14px;
        }

        .panel {
            background: #f7fcf7;
            border: 1px solid #d9eadb;
            border-radius: 8px;
            padding: 14px;
            margin: 14px 0;
        }

        .btn-wrap {
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            background: #00a63e;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 6px;
        }

        .muted {
            color: #6b7280;
            font-size: 12px;
        }

        .email-footer {
            border-top: 1px solid #e5e7eb;
            padding: 14px 24px 18px;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.5;
            background: #ffffff;
        }

        .list {
            margin: 8px 0 0;
            padding-left: 18px;
        }

        .list li {
            margin: 4px 0;
        }

        table.clean {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.clean td {
            padding: 6px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="email-shell">
        <div class="email-card">
            <div class="email-header">
                @if ($logoUrl)
                    <div style="margin: 0 auto 10px; text-align: center;">
                        <img src="{{ $logoUrl }}" alt="{{ $storeName }}"
                            style="max-height: 64px; width: auto; display: inline-block;">
                    </div>
                @endif
                {{-- <p class="brand">{{ $storeName }}</p> --}}
                <p class="title">{{ $title }}</p>
            </div>

            <div class="email-content">
                {{ $slot }}
            </div>

            <div class="email-footer">
                © {{ date('Y') }} {{ $storeName }}
            </div>
        </div>
    </div>
</body>

</html>
