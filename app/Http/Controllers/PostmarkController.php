<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Models\EmailReplyMessage;
use App\Models\EmailRecipient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
            Log::info('Postmark inbound webhook received', [
                'payload_keys' => array_keys($payload),
                'subject' => data_get($payload, 'Subject'),
                'from' => data_get($payload, 'From'),
                'from_full' => data_get($payload, 'FromFull'),
                'to' => data_get($payload, 'To'),
                'original_recipient' => data_get($payload, 'OriginalRecipient'),
            ]);
            $encodedPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);
            $now = now();

            $senderCandidates = $this->extractSenderCandidates($payload);

            Log::info('Postmark sender candidates extracted', [
                'sender_candidates' => $senderCandidates->all(),
                'candidate_count' => $senderCandidates->count(),
            ]);

            if ($senderCandidates->isEmpty()) {
                Log::warning('Postmark inbound missing sender candidates', [
                    'from' => data_get($payload, 'From'),
                    'from_full' => data_get($payload, 'FromFull'),
                    'sender' => data_get($payload, 'Sender'),
                    'reply_to' => data_get($payload, 'ReplyTo'),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Sender email not found in webhook payload.',
                ], 422);
            }

            // Normalize matching against legacy rows where email may include whitespace casing differences.
            $matchingRecipients = EmailRecipient::query()
                ->where(function ($query) use ($senderCandidates): void {
                    foreach ($senderCandidates as $email) {
                        $query->orWhereRaw('LOWER(TRIM(email)) = ?', [$email]);
                    }
                })
                ->whereNull('replied_at')
                ->get(['id', 'email_log_id', 'replied_at']);

            Log::info('Postmark pending recipient matches', [
                'matched_count' => $matchingRecipients->count(),
                'matched_ids' => $matchingRecipients->pluck('id')->values()->all(),
            ]);

            // If no pending rows match, use the most recently replied recipient for ongoing thread tracking.
            if ($matchingRecipients->isEmpty()) {
                $matchingRecipients = EmailRecipient::query()
                    ->where(function ($query) use ($senderCandidates): void {
                        foreach ($senderCandidates as $email) {
                            $query->orWhereRaw('LOWER(TRIM(email)) = ?', [$email]);
                        }
                    })
                    ->whereNotNull('replied_at')
                    ->orderByDesc('replied_at')
                    ->limit(1)
                    ->get(['id', 'email_log_id', 'replied_at']);

                Log::info('Postmark fallback replied recipient match', [
                    'matched_count' => $matchingRecipients->count(),
                    'matched_ids' => $matchingRecipients->pluck('id')->values()->all(),
                ]);
            }

            $updatedCount = 0;

            if ($matchingRecipients->isNotEmpty()) {
                $recipientIds = $matchingRecipients->pluck('id');

                $updatedCount = EmailRecipient::query()
                    ->whereIn('id', $recipientIds)
                    ->update([
                        'reply_payload' => $encodedPayload !== false ? $encodedPayload : null,
                        'updated_at' => $now,
                    ]);

                Log::info('Postmark recipients payload updated', [
                    'updated_count' => $updatedCount,
                    'recipient_ids' => $recipientIds->values()->all(),
                ]);

                $newlyRepliedIds = $matchingRecipients
                    ->whereNull('replied_at')
                    ->pluck('id');

                Log::info('Postmark newly replied recipients resolved', [
                    'newly_replied_count' => $newlyRepliedIds->count(),
                    'newly_replied_ids' => $newlyRepliedIds->values()->all(),
                ]);

                if ($newlyRepliedIds->isNotEmpty()) {
                    EmailRecipient::query()
                        ->whereIn('id', $newlyRepliedIds)
                        ->update([
                            'replied_at' => $now,
                            'updated_at' => $now,
                        ]);

                    Log::info('Postmark replied_at updated', [
                        'updated_ids' => $newlyRepliedIds->values()->all(),
                        'replied_at' => $now->toDateTimeString(),
                    ]);
                }

                // Deduplicate: one history record per email_log only.
                $matchingRecipients
                    ->groupBy('email_log_id')
                    ->each(function ($recipientsInLog) use ($payload, $now): void {
                        $recipient = $recipientsInLog->first();
                        EmailReplyMessage::query()->create([
                            'email_log_id' => $recipient->email_log_id,
                            'email_recipient_id' => $recipient->id,
                            'direction' => 'inbound',
                            'from_email' => data_get($payload, 'FromFull.Email') ?? data_get($payload, 'From'),
                            'subject' => data_get($payload, 'Subject'),
                            'text_body' => data_get($payload, 'TextBody'),
                            'html_body' => data_get($payload, 'HtmlBody'),
                            'payload' => $payload,
                            'received_at' => $now,
                        ]);

                        Log::info('Postmark inbound reply message saved', [
                            'email_log_id' => $recipient->email_log_id,
                            'email_recipient_id' => $recipient->id,
                            'from_email' => data_get($payload, 'FromFull.Email') ?? data_get($payload, 'From'),
                            'received_at' => $now->toDateTimeString(),
                        ]);
                    });

                if ($newlyRepliedIds->isNotEmpty()) {
                    $this->refreshEmailLogReplyCounts($matchingRecipients->pluck('email_log_id')->unique()->values());

                    Log::info('Postmark email log counters refreshed', [
                        'email_log_ids' => $matchingRecipients->pluck('email_log_id')->unique()->values()->all(),
                    ]);
                }
            }

            if ($updatedCount === 0) {
                Log::warning('Postmark inbound webhook did not match pending recipients', [
                    'sender_candidates' => $senderCandidates->all(),
                    'subject' => data_get($payload, 'Subject'),
                    'from' => data_get($payload, 'From'),
                    'from_full' => data_get($payload, 'FromFull'),
                    'to' => data_get($payload, 'To'),
                    'original_recipient' => data_get($payload, 'OriginalRecipient'),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => $updatedCount > 0
                    ? 'Reply tracked successfully.'
                    : 'No pending recipient found for this sender.',
                'sender_candidates' => $senderCandidates->all(),
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
     * Build normalized sender email candidates from common Postmark fields.
     */
    private function extractSenderCandidates(array $payload): Collection
    {
        $rawCandidates = [
            data_get($payload, 'FromFull.Email'),
            data_get($payload, 'From'),
            data_get($payload, 'Sender'),
            data_get($payload, 'ReplyTo'),
        ];

        return collect($rawCandidates)
            ->filter(fn($value): bool => filled($value))
            ->flatMap(function ($value): array {
                if (!is_string($value)) {
                    return [];
                }

                return $this->extractEmailsFromText($value);
            })
            ->map(fn(string $email): string => strtolower(trim($email)))
            ->filter(fn(string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->unique()
            ->values();
    }

    /**
     * Extract one or more emails from a free-form header/text string.
     *
     * @return array<int, string>
     */
    private function extractEmailsFromText(string $value): array
    {
        $emails = [];

        if (preg_match_all('/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', $value, $matches) > 0) {
            $emails = $matches[0];
        }

        if ($emails === [] && filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $emails[] = $value;
        }

        return $emails;
    }

    /**
     * Recompute reply counters on affected email logs.
     */
    private function refreshEmailLogReplyCounts(Collection $emailLogIds): void
    {
        if ($emailLogIds->isEmpty()) {
            return;
        }

        EmailLog::query()
            ->whereIn('id', $emailLogIds)
            ->get()
            ->each(function (EmailLog $emailLog): void {
                $emailLog->update([
                    'replied_count' => $emailLog->repliedRecipients()->count(),
                    'pending_count' => $emailLog->pendingRecipients()->count(),
                ]);
            });
    }
}
