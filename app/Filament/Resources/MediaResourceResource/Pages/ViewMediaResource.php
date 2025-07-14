<?php

namespace App\Filament\Resources\MediaResourceResource\Pages;

use App\Filament\Resources\MediaResourceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMediaResource extends ViewRecord
{
    protected static string $resource = MediaResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
