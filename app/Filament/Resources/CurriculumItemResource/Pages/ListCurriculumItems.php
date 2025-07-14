<?php

namespace App\Filament\Resources\CurriculumItemResource\Pages;

use App\Filament\Resources\CurriculumItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCurriculumItems extends ListRecords
{
    protected static string $resource = CurriculumItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
