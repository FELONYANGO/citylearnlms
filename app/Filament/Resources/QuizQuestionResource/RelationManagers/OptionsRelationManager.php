<?php

namespace App\Filament\Resources\QuizQuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;

class OptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('option_text')
                ->label('Option Text')
                ->required()
                ->maxLength(255),

            Textarea::make('explanation')
                ->label('Explanation')
                ->helperText('Explain why this answer is correct/incorrect')
                ->rows(3)
                ->columnSpanFull(),

            Toggle::make('is_correct')
                ->label('Correct Answer')
                ->helperText('Mark if this is the correct answer')
                ->default(false),

            TextInput::make('score')
                ->label('Points')
                ->numeric()
                ->default(1)
                ->minValue(0)
                ->maxValue(100)
                ->helperText('Points awarded if this option is selected'),

            Select::make('feedback_type')
                ->label('Feedback Type')
                ->options([
                    'positive' => 'Positive',
                    'negative' => 'Negative',
                    'neutral' => 'Neutral'
                ])
                ->default('neutral')
                ->required(),

            TextInput::make('order')
                ->label('Display Order')
                ->numeric()
                ->default(0)
                ->helperText('Order in which options are displayed'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('order', 'asc')
            ->columns([
                TextColumn::make('option_text')
                    ->label('Option')
                    ->searchable()
                    ->wrap()
                    ->limit(50)
                    ->tooltip(fn($record) => $record->option_text),

                IconColumn::make('is_correct')
                    ->label('Correct')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('score')
                    ->label('Points')
                    ->sortable(),

                TextColumn::make('feedback_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'positive' => 'success',
                        'negative' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('explanation')
                    ->label('Explanation')
                    ->wrap()
                    ->limit(30)
                    ->tooltip(fn($record) => $record->explanation)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->reorderable('order')
            ->defaultSort('order')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // If this is marked as correct, we might want to unmark other options
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
