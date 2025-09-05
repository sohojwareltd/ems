<?php

namespace App\Filament\Actions;

use App\Models\Order;
use App\Services\OrderEmailService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TestOrderEmailAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'test_email';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Test Email')
            ->icon('heroicon-o-envelope')
            ->color('warning')
            ->requiresConfirmation()
            ->modalHeading('Test Order Email')
            ->modalDescription('Select which email type to send as a test.')
            ->modalSubmitActionLabel('Send Test Email')
            ->form([
                \Filament\Forms\Components\Select::make('email_type')
                    ->label('Email Type')
                    ->options([
                        'confirmation' => 'Order Confirmation',
                        'status_update' => 'Status Update',
                        'shipping' => 'Shipping Confirmation',
                        'cancellation' => 'Order Cancellation',
                        'refund' => 'Order Refund',
                        'admin_notification' => 'Admin Notification'
                    ])
                    ->required()
                    ->default('confirmation'),
                \Filament\Forms\Components\TextInput::make('test_email')
                    ->label('Test Email Address')
                    ->email()
                    ->required()
                    ->default(config('mail.admin_email', 'admin@yourstore.com'))
                    ->helperText('Email will be sent to this address instead of the customer.')
            ])
            ->action(function (array $data, Order $record): void {
                $this->handleTestEmail($data, $record);
            });
    }

    protected function handleTestEmail(array $data, Order $record): void
    {
        try {
            $emailService = new OrderEmailService();
            $emailType = $data['email_type'];
            $testEmail = $data['test_email'];

            // Temporarily override the customer email for testing
            $originalEmail = $record->billing_address['email'];
            $record->billing_address['email'] = $testEmail;

            $success = $emailService->testEmail($record, $emailType);

            // Restore original email
            $record->billing_address['email'] = $originalEmail;

            if ($success) {
                Notification::make()
                    ->title('Test Email Sent Successfully')
                    ->body("Test {$emailType} email has been sent to {$testEmail}")
                    ->success()
                    ->send();

                Log::info('Test email sent successfully', [
                    'order_id' => $record->id,
                    'order_number' => $record->order_number,
                    'email_type' => $emailType,
                    'test_email' => $testEmail
                ]);
            } else {
                Notification::make()
                    ->title('Test Email Failed')
                    ->body("Failed to send test {$emailType} email to {$testEmail}")
                    ->danger()
                    ->send();

                Log::error('Test email failed', [
                    'order_id' => $record->id,
                    'order_number' => $record->order_number,
                    'email_type' => $emailType,
                    'test_email' => $testEmail
                ]);
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Test Email Error')
                ->body('An error occurred while sending the test email: ' . $e->getMessage())
                ->danger()
                ->send();

            Log::error('Test email error', [
                'order_id' => $record->id,
                'order_number' => $record->order_number,
                'email_type' => $data['email_type'] ?? 'unknown',
                'test_email' => $data['test_email'] ?? 'unknown',
                'error' => $e->getMessage()
            ]);
        }
    }
} 