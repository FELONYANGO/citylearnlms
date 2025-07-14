<?php

namespace App\Filament\Resources\CurriculumItemResource\Pages;

use App\Filament\Resources\CurriculumItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCurriculumItem extends ViewRecord
{
    protected static string $resource = CurriculumItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
