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
                ->guess(['plan_id'])
                ->integer()
                ->rules(['nullable', 'integer', 'exists:plans,id'])
                ->helperText('Use the plan ID to update an existing plan.'),

            ImportColumn::make('name')
                ->label('Plan Name')
                ->guess(['plan_name'])
                ->rules(['nullable', 'string', 'max:255'])
                ->helperText('Used when Plan ID is not provided.'),

            ImportColumn::make('coupon_code')
                ->label('Access Code')
                ->guess(['access_code', 'coupon'])
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
            $plan = Plan::query()->where('name', $planName)->first();

            if ($plan) {
                return $plan;
            }
        }

        throw ValidationException::withMessages([
            'name' => 'No matching plan was found for this row. Provide a valid Plan ID or Plan Name.',
        ]);
    }

    protected function beforeFill(): void
    {
        $this->data['coupon_code'] = Str::upper(trim((string) ($this->data['coupon_code'] ?? '')));

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

        return "Access code import completed. {$count} plan(s) updated successfully.";
    }
}
