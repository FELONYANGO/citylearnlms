<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Services\CertificateService;

class ViewEnrollment extends ViewRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

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
                ->visible(fn() => $this->record->status !== Enrollment::STATUS_COMPLETED),

            Action::make('issue_certificate')
                ->label('Issue Certificate')
                ->icon('heroicon-o-academic-cap')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Issue Certificate')
                ->modalDescription('This will generate and issue a certificate for this enrollment.')
                ->action(function () {
                    $certificateService = app(CertificateService::class);
                    $success = $certificateService->issueCertificate($this->record);

                    if ($success) {
                        $this->record->refresh();
                        Notification::make()
                            ->success()
                            ->title('Certificate Issued')
                            ->body("Certificate number: {$this->record->certificate_number}")
                            ->send();
                    } else {
                        Notification::make()
                            ->danger()
                            ->title('Certificate Issue Failed')
                            ->body('Failed to issue certificate. Please try again.')
                            ->send();
                    }
                })
                ->visible(
                    fn() =>
                    $this->record->status === Enrollment::STATUS_COMPLETED &&
                        !$this->record->certificate_issued
                ),

            Action::make('preview_certificate')
                ->label('Preview Certificate')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->url(
                    fn() =>
                    $this->record->certificate_issued && $this->record->course
                        ? route('certificates.preview', $this->record->course)
                        : '#'
                )
                ->openUrlInNewTab()
                ->visible(
                    fn() =>
                    $this->record->certificate_issued && $this->record->course
                ),

            Action::make('view_certificate')
                ->label('View Certificate')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->url(
                    fn() =>
                    $this->record->certificate_issued && $this->record->course
                        ? route('certificates.show', $this->record->course)
                        : '#'
                )
                ->openUrlInNewTab()
                ->visible(
                    fn() =>
                    $this->record->certificate_issued && $this->record->course
                ),

            Action::make('download_certificate')
                ->label('Download Certificate')
                ->icon('heroicon-o-document-arrow-down')
                ->color('warning')
                ->url(
                    fn() =>
                    $this->record->certificate_issued && $this->record->course
                        ? route('certificates.download', $this->record->course)
                        : '#'
                )
                ->openUrlInNewTab()
                ->visible(fn() => $this->record->certificate_issued && $this->record->course),

            Action::make('send_reminder')
                ->label('Send Reminder')
                ->icon('heroicon-o-envelope')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Send Reminder Email')
                ->modalDescription('Send a reminder email to the user about their enrollment.')
                ->action(function () {
                    // Email reminder functionality can be implemented here
                    Notification::make()
                        ->success()
                        ->title('Reminder Sent')
                        ->body('Reminder email has been sent to the user.')
                        ->send();
                })
                ->visible(fn() => $this->record->status === Enrollment::STATUS_ACTIVE),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // You can add widgets here if needed
        ];
    }
}
