<?php

namespace App\Filament\Resources\QuizOptionResource\Pages;

use App\Filament\Resources\QuizOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuizOption extends EditRecord
{
    protected static string $resource = QuizOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
