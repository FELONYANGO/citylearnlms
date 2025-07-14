<?php

namespace App\Filament\Resources\HomepageSettingsResource\Pages;

use App\Filament\Resources\HomepageSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomepageSettings extends ListRecords
{
    protected static string $resource = HomepageSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Configure Homepage'),
        ];
    }

    public function getTitle(): string
    {
        return 'Homepage Settings';
    }
}
