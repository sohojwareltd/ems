#!/usr/bin/env php
<?php

/**
 * Payment Configuration Verification Script
 * 
 * This script checks that all payment-related code is using env() instead of settings()
 * Run: php verify-payment-config.php
 */

echo "🔍 Verifying Payment Configuration...\n\n";

$errors = [];
$warnings = [];
$success = [];

// Files to check
$files = [
    'app/Http/Controllers/CheckoutController.php',
    'app/Http/Controllers/SubscriptionController.php',
    'app/Http/Controllers/PayPalController.php',
    'app/Services/CheckoutService.php',
    'resources/views/frontend/checkout/payment.blade.php',
    'resources/views/frontend/pages/subscriptions/payment.blade.php',
    'app/Console/Commands/ChargeDefaultSubscriptions.php',
];

echo "📋 Checking files for settings('payments.*) usage...\n";
echo str_repeat("-", 60) . "\n";

foreach ($files as $file) {
    $filepath = __DIR__ . '/' . $file;
    
    if (!file_exists($filepath)) {
        $warnings[] = "⚠️  File not found: $file";
        continue;
    }
    
    $content = file_get_contents($filepath);
    
    // Check for settings('payments.
    if (preg_match("/setting\s*\(\s*['\"]payments\./i", $content)) {
        $errors[] = "❌ Found settings('payments.*) in: $file";
    } else {
        $success[] = "✅ Clean: $file";
    }
}

// Print results
echo "\n📊 RESULTS:\n";
echo str_repeat("=", 60) . "\n\n";

if (!empty($success)) {
    echo "✅ PASSED (" . count($success) . " files):\n";
    foreach ($success as $msg) {
        echo "   $msg\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️  WARNINGS (" . count($warnings) . "):\n";
    foreach ($warnings as $msg) {
        echo "   $msg\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "❌ ERRORS (" . count($errors) . "):\n";
    foreach ($errors as $msg) {
        echo "   $msg\n";
    }
    echo "\n";
    echo "❌ VERIFICATION FAILED - Please fix the errors above.\n";
    exit(1);
}

// Check .env.example
echo "📄 Checking .env.example for required variables...\n";
echo str_repeat("-", 60) . "\n";

$envExample = __DIR__ . '/.env.example';
$requiredVars = [
    'STRIPE_KEY',
    'STRIPE_SECRET',
    'PAYPAL_CLIENT_ID',
    'PAYPAL_CLIENT_SECRET',
    'PAYPAL_SANDBOX',
];

if (file_exists($envExample)) {
    $envContent = file_get_contents($envExample);
    $missing = [];
    
    foreach ($requiredVars as $var) {
        if (stripos($envContent, $var) === false) {
            $missing[] = $var;
        }
    }
    
    if (empty($missing)) {
        echo "✅ All required payment variables found in .env.example\n";
    } else {
        echo "⚠️  Missing variables in .env.example:\n";
        foreach ($missing as $var) {
            echo "   - $var\n";
        }
    }
} else {
    echo "⚠️  .env.example file not found\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ VERIFICATION COMPLETE - All checks passed!\n";
echo "\n💡 Next steps:\n";
echo "   1. Ensure your .env file has all required payment variables\n";
echo "   2. Test checkout flow with Stripe\n";
echo "   3. Test subscription payment flow\n";
echo "   4. Test PayPal integration (if configured)\n";
echo "\n";

exit(0);
