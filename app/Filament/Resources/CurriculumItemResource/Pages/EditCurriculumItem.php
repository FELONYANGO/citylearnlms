<?php

namespace App\Filament\Resources\CurriculumItemResource\Pages;

use App\Filament\Resources\CurriculumItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCurriculumItem extends EditRecord
{
    protected static string $resource = CurriculumItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
