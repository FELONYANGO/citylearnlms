<?php

namespace App\Filament\Resources\HomepageSettingsResource\Pages;

use App\Filament\Resources\HomepageSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHomepageSettings extends CreateRecord
{
    protected static string $resource = HomepageSettingsResource::class;

    public function getTitle(): string
    {
        return 'Configure Homepage Settings';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
