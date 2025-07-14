<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn(): bool => Auth::user()->hasRole('admin'))
                ->requiresConfirmation()
                ->modalDescription('Are you sure you want to delete this payment? This action cannot be undone.'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Payment updated successfully';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Only allow status updates for most fields
        $currentRecord = $this->record;

        // Prevent modification of core payment data once processing has begun
        if (in_array($currentRecord->status, ['completed', 'failed', 'cancelled'])) {
            // Only allow status field to be modified for completed/failed payments
            $data = array_intersect_key($data, ['status' => '']);
            $data = array_merge($currentRecord->toArray(), $data);
        }

        return $data;
    }
}
