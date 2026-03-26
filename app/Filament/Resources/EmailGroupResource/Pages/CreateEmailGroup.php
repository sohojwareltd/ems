<?php

namespace App\Filament\Resources\EmailGroupResource\Pages;

use App\Filament\Resources\EmailGroupResource;
use App\Models\EmailGroup;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CreateEmailGroup extends CreateRecord
{
    protected static string $resource = EmailGroupResource::class;

    protected string|array|null $uploadedEmailFile = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->uploadedEmailFile = $data['email_file'] ?? null;
        unset($data['email_file']);

        // Convert TagsInput array to comma-separated string for parsing
        if (! empty($data['email']) && is_array($data['email'])) {
            $data['email'] = implode(',', $data['email']);
        }

        if (! empty($data['email'])) {
            $data['email'] = strtolower(trim((string) $data['email']));
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        if (! empty($data['title'])) {
            $data['title'] = strtolower(trim((string) $data['title']));
        }

        $emailsFromText = $this->parseEmailsFromText((string) ($data['email'] ?? ''));
        $emailsFromFiles = $this->parseEmailsFromUploadedFiles($this->uploadedEmailFile);
        $emails = array_values(array_unique(array_merge($emailsFromText, $emailsFromFiles)));

        // Parent selected = child emails mode (manual comma-separated + Excel upload)
        if (! empty($data['parent_id'])) {
            if (empty($emails)) {
                throw ValidationException::withMessages([
                    'email' => 'No valid emails found. Please enter comma-separated emails or upload a valid Excel file.',
                ]);
            }

            $existingEmails = EmailGroup::query()
                ->where('parent_id', $data['parent_id'])
                ->whereIn('email', $emails)
                ->pluck('email')
                ->map(fn (string $email) => strtolower($email))
                ->all();

            $newEmails = array_values(array_diff($emails, $existingEmails));
            $created = null;

            foreach ($newEmails as $email) {
                $created = EmailGroup::create([
                    'email' => $email,
                    'parent_id' => $data['parent_id'],
                ]);
            }

            $count = count($newEmails);
            $skipped = count($emails) - $count;

            Notification::make()
                ->title($count > 0 ? "{$count} email(s) imported successfully. {$skipped} duplicate skipped." : 'All emails already exist. No new entries added.')
                ->status($count > 0 ? 'success' : 'warning')
                ->send();

            if ($created) {
                return $created;
            }

            $existingRecord = EmailGroup::query()
                ->whereNotNull('email')
                ->where('parent_id', $data['parent_id'])
                ->whereIn('email', $emails)
                ->first();

            if ($existingRecord) {
                return $existingRecord;
            }

            throw ValidationException::withMessages([
                'email' => 'No new email was created.',
            ]);
        }

        // No parent selected = create a parent group and optionally create child emails under it.
        unset($data['email']);
        $group = EmailGroup::create($data);

        if (empty($emails)) {
            return $group;
        }

        $existingEmails = EmailGroup::query()
            ->where('parent_id', $group->getKey())
            ->whereIn('email', $emails)
            ->pluck('email')
            ->map(fn (string $email) => strtolower($email))
            ->all();

        $newEmails = array_values(array_diff($emails, $existingEmails));

        foreach ($newEmails as $email) {
            EmailGroup::create([
                'email' => $email,
                'parent_id' => $group->getKey(),
                'title' => null,
            ]);
        }

        $count = count($newEmails);
        $skipped = count($emails) - $count;

        Notification::make()
            ->title($count > 0 ? "Group created and {$count} email(s) imported successfully. {$skipped} duplicate skipped." : 'Group created, but all provided emails already exist. No child emails were added.')
            ->status($count > 0 ? 'success' : 'warning')
            ->send();

        return $group;
    }

    private function parseEmailsFromText(string $input): array
    {
        if (blank($input)) {
            return [];
        }

        $parts = preg_split('/[,;\n\r]+/', $input) ?: [];
        $emails = [];

        foreach ($parts as $part) {
            $value = strtolower(trim($part));
            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $value;
            }
        }

        return array_values(array_unique($emails));
    }

    private function parseEmailsFromUploadedFiles(string|array|null $uploadedEmailFile): array
    {
        if (blank($uploadedEmailFile)) {
            return [];
        }

        $files = is_array($uploadedEmailFile) ? $uploadedEmailFile : [$uploadedEmailFile];
        $emails = [];

        foreach ($files as $file) {
            $filePath = $this->normalizeUploadedPath($file);

            if (blank($filePath)) {
                continue;
            }

            $relativePath = str_starts_with($filePath, 'email-imports/')
                ? $filePath
                : 'email-imports/' . $filePath;

            $path = Storage::disk('local')->path($relativePath);
            $emails = array_merge($emails, $this->parseEmailsFromFile($path));
            Storage::disk('local')->delete($relativePath);
        }

        return array_values(array_unique($emails));
    }

    private function parseEmailsFromFile(string $path): array
    {
        if (! file_exists($path)) {
            return [];
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $emails = [];

        if (in_array($extension, ['xls', 'xlsx'])) {
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                foreach ($spreadsheet->getActiveSheet()->toArray() as $row) {
                    $emails = array_merge($emails, $this->extractEmailsFromRow($row));
                }
            } catch (\Throwable $exception) {
                Log::error('EmailGroup Excel parse failed', [
                    'path' => $path,
                    'message' => $exception->getMessage(),
                ]);

                throw ValidationException::withMessages([
                    'email_file' => 'Failed to read Excel file. Please check the file format (xlsx/xls) and try again.',
                ]);
            }
        } else {
            if (($handle = fopen($path, 'r')) !== false) {
                while (($row = fgetcsv($handle)) !== false) {
                    $emails = array_merge($emails, $this->extractEmailsFromRow($row));
                }
                fclose($handle);
            }
        }

        return array_values(array_unique($emails));
    }

    private function extractEmailsFromRow(array $row): array
    {
        $results = [];

        foreach ($row as $cell) {
            $value = strtolower(trim((string) $cell));

            // Skip common header values like "Email" / "E-mail"
            if (in_array($value, ['email', 'e-mail', 'mail'], true)) {
                continue;
            }

            if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $results[] = $value;
            }
        }

        return $results;
    }

    private function normalizeUploadedPath(mixed $file): ?string
    {
        if (is_string($file) && $file !== '') {
            return $file;
        }

        if (is_array($file)) {
            foreach (['path', 'file', 'name'] as $key) {
                if (isset($file[$key]) && is_string($file[$key]) && $file[$key] !== '') {
                    return $file[$key];
                }
            }

            foreach ($file as $value) {
                $normalized = $this->normalizeUploadedPath($value);
                if ($normalized) {
                    return $normalized;
                }
            }
        }

        return null;
    }
}
