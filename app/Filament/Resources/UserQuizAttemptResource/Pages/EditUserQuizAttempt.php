<?php

namespace App\Filament\Resources\UserQuizAttemptResource\Pages;

use App\Filament\Resources\UserQuizAttemptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserQuizAttempt extends EditRecord
{
    protected static string $resource = UserQuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
