<?php

namespace App\Filament\Resources\QuizOptionResource\Pages;

use App\Filament\Resources\QuizOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuizOptions extends ListRecords
{
    protected static string $resource = QuizOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
