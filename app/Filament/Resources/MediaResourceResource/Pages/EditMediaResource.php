<?php

namespace App\Filament\Resources\MediaResourceResource\Pages;

use App\Filament\Resources\MediaResourceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMediaResource extends EditRecord
{
    protected static string $resource = MediaResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
