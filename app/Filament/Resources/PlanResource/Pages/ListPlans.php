<?php

namespace App\Filament\Resources\PlanResource\Pages;

use App\Filament\Resources\PlanResource;
use App\Models\Plan;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ListPlans extends ListRecords
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('importAccessCodesExcel')
                ->label('Import Access Codes (XLSX)')
                ->icon('heroicon-o-arrow-up-tray')
                ->modalHeading('Import Access Codes from Excel')
                ->modalSubmitActionLabel('Import Excel File')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('Excel File')
                        ->disk('local')
                        ->directory('imports/plan-access-codes')
                        ->acceptedFileTypes([
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->required()
                        ->helperText('Expected columns: Plan Name, Access Code, optional Max Uses, Active, Coupon Enabled.'),
                ])
                ->action(function (array $data): void {
                    $this->importAccessCodesFromExcel($data['file'] ?? null);
                }),
        ];
    }

    protected function importAccessCodesFromExcel(string | array | null $uploadedFile): void
    {
        $relativePath = $this->normalizeUploadedPath($uploadedFile);

        if (blank($relativePath)) {
            throw ValidationException::withMessages([
                'file' => 'Please upload a valid Excel file.',
            ]);
        }

        $relativePath = str_starts_with($relativePath, 'imports/plan-access-codes/')
            ? $relativePath
            : 'imports/plan-access-codes/' . $relativePath;

        $path = Storage::disk('local')->path($relativePath);

        try {
            $rows = \PhpOffice\PhpSpreadsheet\IOFactory::load($path)
                ->getActiveSheet()
                ->toArray(null, true, true, false);

            if ($rows === []) {
                throw ValidationException::withMessages([
                    'file' => 'The uploaded Excel file is empty.',
                ]);
            }

            $header = array_map(
                fn ($value): string => $this->normalizeHeader((string) $value),
                array_shift($rows) ?: [],
            );

            $successful = 0;
            $notFoundCount = 0;
            $emptyCodeCount = 0;
            $duplicateSkipped = 0;
            $createdCount = 0;
            $firstRowDebug = null;

            foreach ($rows as $row) {
                if ($this->rowIsEmpty($row)) {
                    continue;
                }

                $data = $this->mapRowToHeader($header, $row);

                // Capture first data row for debug
                if ($firstRowDebug === null) {
                    $pairs = [];
                    foreach ($data as $k => $v) {
                        $pairs[] = "{$k}=\"" . (is_null($v) ? 'NULL' : $v) . "\"";
                    }
                    $firstRowDebug = 'Row 1 raw: ' . implode(', ', $pairs);
                }

                $wasCreated = false;
                $plan = $this->resolveOrCreatePlanFromRow($data, $wasCreated);

                if (! $plan) {
                    $notFoundCount++;
                    continue;
                }

                if ($wasCreated) {
                    $createdCount++;
                }

                $couponCode = Str::upper(trim((string) ($this->getCellValue($data, [
                    'access_code',
                    'coupon_code',
                    'code',
                    'accesscode',
                ]) ?? '')));

                if ($couponCode === '') {
                    $emptyCodeCount++;
                    continue;
                }

                $duplicateExists = Plan::query()
                    ->where('coupon_code', $couponCode)
                    ->whereKeyNot($plan->getKey())
                    ->exists();

                if ($duplicateExists) {
                    $duplicateSkipped++;
                    continue;
                }

                $plan->update([
                    'coupon_code' => $couponCode,
                    'is_coupon_enabled' => $this->normalizeBoolean($this->getCellValue($data, [
                        'coupon_enabled',
                        'is_coupon_enabled',
                        'enable_access_code',
                        'access_code_enabled',
                    ], true)),
                    'coupon_max_uses' => $this->normalizeNullableInteger($this->getCellValue($data, [
                        'max_uses',
                        'coupon_max_uses',
                        'maximum_uses',
                        'max_use',
                    ])),
                    'active' => $this->normalizeBoolean($this->getCellValue($data, [
                        'active',
                        'is_active',
                        'status',
                    ], true)),
                ]);

                $successful++;
            }

            if ($successful === 0) {
                $parts = [];

                if ($notFoundCount > 0) {
                    $parts[] = "{$notFoundCount} row(s): plan name not found in DB.";
                }

                if ($emptyCodeCount > 0) {
                    $parts[] = "{$emptyCodeCount} row(s): access_code column was empty.";
                }

                if ($duplicateSkipped > 0) {
                    $parts[] = "{$duplicateSkipped} row(s): access code already used by another plan.";
                }

                if ($firstRowDebug) {
                    $parts[] = $firstRowDebug;
                }

                Notification::make()
                    ->title('No rows were imported')
                    ->body(implode(' | ', $parts) ?: 'No data rows found.')
                    ->warning()
                    ->send();

                return;
            }

            $body = "{$successful} plan(s) updated.";

            if ($createdCount > 0) {
                $body .= " {$createdCount} plan(s) created.";
            }

            if ($notFoundCount > 0) {
                $body .= " {$notFoundCount} row(s) skipped (plan name not found).";
            }

            if ($emptyCodeCount > 0) {
                $body .= " {$emptyCodeCount} row(s) skipped (empty access code).";
            }

            if ($duplicateSkipped > 0) {
                $body .= " {$duplicateSkipped} row(s) skipped (duplicate access code).";
            }

            Notification::make()
                ->title('Excel import completed')
                ->body($body)
                ->success()
                ->send();
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            throw ValidationException::withMessages([
                'file' => 'Failed to read the Excel file. Please use a valid .xlsx or .xls file with a header row.',
            ]);
        } finally {
            Storage::disk('local')->delete($relativePath);
        }
    }

    protected function mapRowToHeader(array $header, array $row): array
    {
        $mapped = [];

        foreach ($header as $index => $column) {
            if ($column === '') {
                continue;
            }

            $mapped[$column] = $row[$index] ?? null;
        }

        return $mapped;
    }

    protected function resolveOrCreatePlanFromRow(array $data, bool &$wasCreated = false): ?Plan
    {
        $wasCreated = false;

        $planId = $this->normalizeNullableInteger($this->getCellValue($data, ['id', 'plan_id', 'planid']));

        if (filled($planId)) {
            $plan = Plan::query()->find($planId);

            if ($plan) {
                return $plan;
            }
        }

        $planName = trim((string) ($this->getCellValue($data, ['plan_name', 'name', 'plan_title', 'title', 'plan']) ?? ''));

        if ($planName !== '') {
            $existing = Plan::query()
                ->whereRaw('LOWER(TRIM(name)) = ?', [Str::lower($planName)])
                ->first();

            if ($existing) {
                return $existing;
            }

            $price = $this->normalizeNullableFloat($this->getCellValue($data, ['price']));
            $currency = Str::lower(trim((string) ($this->getCellValue($data, ['currency'], 'usd') ?? 'usd')));
            $interval = $this->normalizeInterval((string) ($this->getCellValue($data, ['interval'], 'month') ?? 'month'));

            if ($price === null || $currency === '' || $interval === null) {
                return null;
            }

            $plan = Plan::query()->create([
                'name' => $planName,
                'description' => $this->normalizeNullableString($this->getCellValue($data, ['description'])),
                'price' => $price,
                'currency' => $currency,
                'interval' => $interval,
                'interval_count' => $this->normalizeNullableInteger($this->getCellValue($data, ['interval_count']), 1),
                'trial_period_days' => $this->normalizeNullableIntegerWithZero($this->getCellValue($data, ['trial_period_days'])),
                'active' => $this->normalizeBoolean($this->getCellValue($data, ['active', 'is_active', 'status'], true)),
                'is_hide' => $this->normalizeBoolean($this->getCellValue($data, ['hide', 'is_hide'], false)),
            ]);

            $wasCreated = true;

            return $plan;
        }

        return null;
    }

    protected function getCellValue(array $data, array $keys, mixed $default = null): mixed
    {
        foreach ($keys as $key) {
            if (! array_key_exists($key, $data)) {
                continue;
            }

            $value = $data[$key];

            if ($this->isNullish($value)) {
                continue;
            }

            if (filled($value)) {
                return $value;
            }
        }

        return $default;
    }

    protected function normalizeHeader(string $value): string
    {
        $header = (string) Str::of($value)
            ->trim()
            ->replace(['-', '/', '\\'], ' ')
            ->snake()
            ->lower();

        return match ($header) {
            'planid' => 'plan_id',
            'plan_name_' => 'plan_name',
            'accesscode' => 'access_code',
            'access_code_' => 'access_code',
            'couponenabled' => 'coupon_enabled',
            'maximumuses' => 'maximum_uses',
            default => $header,
        };
    }

    protected function normalizeBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (bool) $value;
        }

        return in_array(Str::lower(trim((string) $value)), ['1', 'true', 'yes', 'y', 'on', 'active'], true);
    }

    protected function normalizeNullableInteger(mixed $value, ?int $default = null): ?int
    {
        if ($this->isNullish($value) || blank($value)) {
            return $default;
        }

        return max(1, (int) $value);
    }

    protected function normalizeNullableIntegerWithZero(mixed $value, ?int $default = null): ?int
    {
        if ($this->isNullish($value) || blank($value)) {
            return $default;
        }

        return max(0, (int) $value);
    }

    protected function normalizeNullableFloat(mixed $value): ?float
    {
        if ($this->isNullish($value) || blank($value)) {
            return null;
        }

        return (float) $value;
    }

    protected function normalizeNullableString(mixed $value): ?string
    {
        if ($this->isNullish($value) || blank($value)) {
            return null;
        }

        return trim((string) $value);
    }

    protected function normalizeInterval(string $value): ?string
    {
        $normalized = Str::lower(trim($value));

        return match ($normalized) {
            'day', 'daily' => 'day',
            'week', 'weekly' => 'week',
            'month', 'monthly' => 'month',
            'year', 'yearly', 'annual' => 'year',
            default => null,
        };
    }

    protected function isNullish(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }

        if (is_string($value)) {
            return Str::lower(trim($value)) === 'null';
        }

        return false;
    }

    protected function rowIsEmpty(array $row): bool
    {
        foreach ($row as $cell) {
            if (filled($cell)) {
                return false;
            }
        }

        return true;
    }

    protected function normalizeUploadedPath(mixed $file): ?string
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
