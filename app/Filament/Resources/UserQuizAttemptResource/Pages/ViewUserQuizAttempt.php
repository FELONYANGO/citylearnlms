<?php

namespace App\Filament\Resources\UserQuizAttemptResource\Pages;

use App\Filament\Resources\UserQuizAttemptResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserQuizAttempt extends ViewRecord
{
    protected static string $resource = UserQuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
