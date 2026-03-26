<?php

namespace App\Http\Controllers;

use App\Models\EmailRecipient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostmarkController extends Controller
{
    /**
     * Handle Postmark inbound webhook and mark recipients as replied.
     */
    public function handleInbound(Request $request): JsonResponse
    {
        try {
            $payload = $request->all();

            // Postmark usually sends sender in FromFull.Email.
            $fromEmail = data_get($payload, 'FromFull.Email');

            // Fallback: parse plain From header like "John <john@example.com>".
            if (blank($fromEmail)) {
                $fromEmail = $this->extractEmailFromFromHeader((string) data_get($payload, 'From', ''));
            }

            if (blank($fromEmail)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sender email not found in webhook payload.',
                ], 422);
            }

            $fromEmail = strtolower(trim($fromEmail));

            // Match pending recipients by sender email and mark as replied.
            $updatedCount = EmailRecipient::query()
                ->whereRaw('LOWER(email) = ?', [$fromEmail])
                ->whereNull('replied_at')
                ->update([
                    'replied_at' => now(),
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => $updatedCount > 0
                    ? 'Reply tracked successfully.'
                    : 'No pending recipient found for this sender.',
                'sender_email' => $fromEmail,
                'updated_count' => $updatedCount,
            ]);
        } catch (\Throwable $e) {
            Log::error('Postmark inbound webhook failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to process inbound webhook.',
            ], 500);
        }
    }

    /**
     * Extract email address from From header text.
     */
    private function extractEmailFromFromHeader(string $from): ?string
    {
        if (preg_match('/<([^>]+)>/', $from, $matches) === 1) {
            return $matches[1];
        }

        if (filter_var($from, FILTER_VALIDATE_EMAIL)) {
            return $from;
        }

        return null;
    }
}
