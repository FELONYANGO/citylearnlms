<?php

namespace App\Filament\Resources\HomepageSettingsResource\Pages;

use App\Filament\Resources\HomepageSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomepageSettings extends EditRecord
{
    protected static string $resource = HomepageSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Edit Homepage Settings';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
