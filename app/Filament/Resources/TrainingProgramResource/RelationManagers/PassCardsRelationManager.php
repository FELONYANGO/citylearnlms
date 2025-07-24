<?php

namespace App\Filament\Resources\TrainingProgramResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class PassCardsRelationManager extends RelationManager
{
    protected static string $relationship = 'passCards';
    protected static ?string $title = 'Pass Cards';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('template_id')
                    ->label('Pass Card Template')
                    ->relationship('template', 'name')
                    ->required(),
                Forms\Components\KeyValue::make('metadata')
                    ->label('Metadata')
                    ->keyLabel('Key')
                    ->valueLabel('Value')
                    ->nullable(),
                Forms\Components\Textarea::make('notes')
                    ->rows(3)
                    ->maxLength(1000)
                    ->nullable(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Pass Card Name'),
                Tables\Columns\TextColumn::make('license_number')->label('License Number'),
                Tables\Columns\TextColumn::make('issue_date')->dateTime(),
                Tables\Columns\BadgeColumn::make('is_active')
                    ->label('Status')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ]),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
