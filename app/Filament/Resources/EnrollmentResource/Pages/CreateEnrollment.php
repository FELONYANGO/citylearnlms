<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default values
        $data['enrolled_at'] = $data['enrolled_at'] ?? now();
        $data['status'] = $data['status'] ?? Enrollment::STATUS_PENDING;
        $data['payment_status'] = $data['payment_status'] ?? Enrollment::PAYMENT_PENDING;
        $data['progress_percentage'] = $data['progress_percentage'] ?? 0;
        $data['paid_amount'] = $data['paid_amount'] ?? 0;
        $data['discount_amount'] = $data['discount_amount'] ?? 0;
        $data['certificate_issued'] = $data['certificate_issued'] ?? false;

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Check for duplicate enrollment
        $existingEnrollment = null;
        if ($record->course_id) {
            $existingEnrollment = Enrollment::where('user_id', $record->user_id)
                ->where('course_id', $record->course_id)
                ->whereNotIn('status', [Enrollment::STATUS_CANCELLED, Enrollment::STATUS_EXPIRED])
                ->first();
        } elseif ($record->program_id) {
            $existingEnrollment = Enrollment::where('user_id', $record->user_id)
                ->where('program_id', $record->program_id)
                ->whereNotIn('status', [Enrollment::STATUS_CANCELLED, Enrollment::STATUS_EXPIRED])
                ->first();
        }

        if ($existingEnrollment && $existingEnrollment->id !== $record->id) {
            Notification::make()
                ->warning()
                ->title('Duplicate Enrollment Warning')
                ->body('User already has an active enrollment for this course/program.')
                ->send();
        } else {
            Notification::make()
                ->success()
                ->title('Enrollment Created')
                ->body('The enrollment has been created successfully.')
                ->send();
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Enrollment created successfully';
    }
}
