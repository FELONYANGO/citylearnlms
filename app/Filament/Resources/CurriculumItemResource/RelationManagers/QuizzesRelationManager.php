<?php

namespace App\Filament\Resources\CurriculumItemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuizzesRelationManager extends RelationManager
{
    protected static string $relationship = 'quizzes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('instructions')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('time_limit')
                    ->numeric()
                    ->suffix('minutes')
                    ->nullable(),
                Forms\Components\TextInput::make('passing_score')
                    ->numeric()
                    ->suffix('%')
                    ->nullable(),
                Forms\Components\Toggle::make('is_practice')
                    ->label('Practice Quiz'),
                Forms\Components\Toggle::make('is_final')
                    ->label('Final Quiz'),
                Forms\Components\Toggle::make('randomize_questions')
                    ->label('Randomize Questions'),
                Forms\Components\TextInput::make('max_attempts')
                    ->numeric()
                    ->nullable(),
                Forms\Components\Select::make('show_feedback')
                    ->options([
                        'immediate' => 'Immediate',
                        'after_completion' => 'After Completion',
                        'none' => 'No Feedback'
                    ])
                    ->default('after_completion'),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived'
                    ])
                    ->default('draft'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'draft',
                        'success' => 'published',
                        'gray' => 'archived',
                    ]),

                IconColumn::make('is_practice')
                    ->label('Practice')
                    ->boolean()
                    ->trueIcon('heroicon-o-academic-cap')
                    ->falseIcon('heroicon-o-x-mark'),

                IconColumn::make('is_final')
                    ->label('Final')
                    ->boolean()
                    ->trueIcon('heroicon-o-trophy')
                    ->falseIcon('heroicon-o-x-mark'),

                TextColumn::make('time_limit')
                    ->label('Time')
                    ->formatStateUsing(fn($state) => $state ? "{$state} mins" : '-')
                    ->sortable(),

                TextColumn::make('passing_score')
                    ->label('Pass %')
                    ->formatStateUsing(fn($state) => $state ? "{$state}%" : '-')
                    ->sortable(),

                TextColumn::make('max_attempts')
                    ->label('Attempts')
                    ->formatStateUsing(fn($state) => $state ?: 'Unlimited')
                    ->sortable(),

                BadgeColumn::make('show_feedback')
                    ->label('Feedback')
                    ->colors([
                        'warning' => 'immediate',
                        'success' => 'after_completion',
                        'gray' => 'none',
                    ]),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
