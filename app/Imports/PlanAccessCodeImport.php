<?php

namespace App\Imports;

use App\Models\Plan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PlanAccessCodeImport extends Importer
{
    protected static ?string $model = Plan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id')
                ->label('Plan ID')
                ->guess(['plan_id', 'id', 'Plan ID'])
                ->integer()
                ->rules(['nullable', 'integer', 'exists:plans,id'])
                ->helperText('Use the plan ID to update an existing plan.'),

            ImportColumn::make('name')
                ->label('Plan Name')
                ->guess(['plan_name', 'name', 'Name', 'Plan Name'])
                ->rules(['nullable', 'string', 'max:255'])
                ->helperText('Used when Plan ID is not provided.'),

            ImportColumn::make('description')
                ->label('Description')
                ->guess(['Description'])
                ->rules(['nullable', 'string']),

            ImportColumn::make('price')
                ->label('Price')
                ->numeric()
                ->guess(['Price'])
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('currency')
                ->label('Currency')
                ->guess(['Currency'])
                ->rules(['nullable', 'string', 'size:3'])
                ->fillRecordUsing(function (Plan $record, string $state): void {
                    $record->currency = Str::lower(trim($state));
                }),

            ImportColumn::make('interval')
                ->label('Interval')
                ->guess(['Interval'])
                ->rules(['nullable', 'string', 'in:day,week,month,year']),

            ImportColumn::make('interval_count')
                ->label('Interval Count')
                ->integer()
                ->guess(['Interval count'])
                ->rules(['nullable', 'integer', 'min:1'])
                ->ignoreBlankState(),

            ImportColumn::make('trial_period_days')
                ->label('Trial Period Days')
                ->integer()
                ->guess(['Trial period days'])
                ->rules(['nullable', 'integer', 'min:0'])
                ->ignoreBlankState(),

            ImportColumn::make('is_hide')
                ->label('Hide')
                ->boolean()
                ->guess(['hide', 'Hide'])
                ->rules(['nullable', 'boolean'])
                ->ignoreBlankState(),

            ImportColumn::make('coupon_code')
                ->label('Access Code')
                ->guess(['access_code', 'coupon', 'coupon_code', 'Access code', 'Access Code'])
                ->requiredMapping()
                ->rules([
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('plans', 'coupon_code')->where(fn ($query) => $query
                        ->where('is_coupon_enabled', true)
                        ->where('active', true)
                    ),
                ])
                ->fillRecordUsing(function (Plan $record, string $state): void {
                    $record->coupon_code = Str::upper(trim($state));
                }),

            ImportColumn::make('coupon_max_uses')
                ->label('Maximum Uses')
                ->guess(['max_uses'])
                ->integer()
                ->rules(['nullable', 'integer', 'min:1'])
                ->ignoreBlankState(),

            ImportColumn::make('active')
                ->label('Active')
                ->boolean()
                ->guess(['is_active'])
                ->rules(['nullable', 'boolean'])
                ->ignoreBlankState(),

            ImportColumn::make('is_coupon_enabled')
                ->label('Enable Access Code')
                ->boolean()
                ->guess(['coupon_enabled', 'access_code_enabled'])
                ->rules(['nullable', 'boolean'])
                ->ignoreBlankState(),
        ];
    }

    public function resolveRecord(): ?Plan
    {
        $planId = $this->data['id'] ?? null;
        $planName = trim((string) ($this->data['name'] ?? ''));

        if (filled($planId)) {
            $plan = Plan::find($planId);

            if ($plan) {
                return $plan;
            }
        }

        if ($planName !== '') {
            $normalizedPlanName = Str::lower(trim($planName));

            $plan = Plan::query()
                ->whereRaw('LOWER(TRIM(name)) = ?', [$normalizedPlanName])
                ->first();

            if ($plan) {
                return $plan;
            }
        }

        if ($planName === '') {
            throw ValidationException::withMessages([
                'name' => 'Plan Name is required when Plan ID is empty.',
            ]);
        }

        $requiredForCreate = ['price', 'currency', 'interval'];
        $missing = collect($requiredForCreate)
            ->filter(fn (string $field) => blank($this->data[$field] ?? null))
            ->values()
            ->all();

        if ($missing !== []) {
            throw ValidationException::withMessages([
                'name' => 'Plan not found by name, and missing required create fields: ' . implode(', ', $missing) . '.',
            ]);
        }

        return new Plan();
    }

    protected function beforeFill(): void
    {
        // Convert literal "NULL" strings to null
        foreach ($this->data as $key => $value) {
            if ($value === 'NULL' || $value === 'null') {
                $this->data[$key] = null;
            }
        }

        $this->data['coupon_code'] = Str::upper(trim((string) ($this->data['coupon_code'] ?? '')));

        $interval = Str::lower(trim((string) ($this->data['interval'] ?? '')));
        $intervalMap = [
            'daily' => 'day',
            'day' => 'day',
            'weekly' => 'week',
            'week' => 'week',
            'monthly' => 'month',
            'month' => 'month',
            'yearly' => 'year',
            'annual' => 'year',
            'year' => 'year',
        ];
        if ($interval !== '' && array_key_exists($interval, $intervalMap)) {
            $this->data['interval'] = $intervalMap[$interval];
        }

        if (! array_key_exists('interval_count', $this->data) || blank($this->data['interval_count'])) {
            $this->data['interval_count'] = 1;
        }

        if (! array_key_exists('currency', $this->data) || blank($this->data['currency'])) {
            $this->data['currency'] = 'usd';
        }

        if (! array_key_exists('is_coupon_enabled', $this->data) || blank($this->data['is_coupon_enabled'])) {
            $this->data['is_coupon_enabled'] = true;
        }

        if (! array_key_exists('active', $this->data) || blank($this->data['active'])) {
            $this->data['active'] = true;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $count = $import->successful_rows;

        return "Access code import completed. {$count} plan(s) created/updated successfully.";
    }
}
