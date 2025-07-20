<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class EditEnrollment extends EditRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),

            Action::make('mark_completed')
                ->label('Mark as Completed')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Mark Enrollment as Completed')
                ->modalDescription('This will mark the enrollment as completed and set progress to 100%.')
                ->action(function () {
                    $this->record->markAsCompleted();

                    Notification::make()
                        ->success()
                        ->title('Enrollment Completed')
                        ->body('The enrollment has been marked as completed.')
                        ->send();
                })
                ->visible(fn () => $this->record->status !== Enrollment::STATUS_COMPLETED),

            Action::make('issue_certificate')
                ->label('Issue Certificate')
                ->icon('heroicon-o-academic-cap')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Issue Certificate')
                ->modalDescription('This will generate and issue a certificate for this enrollment.')
                ->action(function () {
                    $certificateNumber = 'CERT-' . strtoupper(uniqid());
                    $this->record->issueCertificate($certificateNumber);

                    Notification::make()
                        ->success()
                        ->title('Certificate Issued')
                        ->body("Certificate number: {$certificateNumber}")
                        ->send();
                })
                ->visible(fn () =>
                    $this->record->status === Enrollment::STATUS_COMPLETED &&
                    !$this->record->certificate_issued
                ),

            Action::make('update_progress')
                ->label('Update Progress')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('info')
                ->form([
                    \Filament\Forms\Components\TextInput::make('progress')
                        ->label('Progress Percentage')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->required()
                        ->suffix('%'),
                ])
                ->action(function (array $data) {
                    $this->record->updateProgress($data['progress']);

                    Notification::make()
                        ->success()
                        ->title('Progress Updated')
                        ->body("Progress updated to {$data['progress']}%")
                        ->send();
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        // Auto-complete if progress reaches 100%
        if ($record->progress_percentage >= 100 && $record->status !== Enrollment::STATUS_COMPLETED) {
            $record->markAsCompleted();

            Notification::make()
                ->info()
                ->title('Auto-Completed')
                ->body('Enrollment automatically marked as completed due to 100% progress.')
                ->send();
        }

        // Auto-issue certificate if completed and no certificate
        if ($record->status === Enrollment::STATUS_COMPLETED && !$record->certificate_issued) {
            $certificateNumber = 'CERT-' . strtoupper(uniqid());
            $record->issueCertificate($certificateNumber);

            Notification::make()
                ->info()
                ->title('Certificate Auto-Issued')
                ->body("Certificate automatically issued: {$certificateNumber}")
                ->send();
        }
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Enrollment updated successfully';
    }
}
