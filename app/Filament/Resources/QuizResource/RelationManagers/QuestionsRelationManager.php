<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use App\Models\QuizQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    protected static ?string $title = 'Questions';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('question_text')
                ->required()
                ->label('Question'),

            Forms\Components\Select::make('question_type')
                ->required()
                ->options([
                    'multiple_choice' => 'Multiple Choice',
                    'true_false' => 'True/False',
                    'short_answer' => 'Short Answer',
                ])
                ->default('multiple_choice'),

            Forms\Components\TextInput::make('points')
                ->numeric()
                ->default(1),

            Forms\Components\Textarea::make('explanation'),

            Forms\Components\Toggle::make('is_required')
                ->label('Is Required?')
                ->default(true),

            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question_text')->label('Question'),
                Tables\Columns\BadgeColumn::make('question_type'),
                Tables\Columns\TextColumn::make('points'),
                Tables\Columns\IconColumn::make('is_required')->boolean(),
                Tables\Columns\TextColumn::make('order'),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
