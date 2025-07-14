<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizQuestionResource\Pages;
use App\Filament\Resources\QuizQuestionResource\RelationManagers;
use App\Models\QuizQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class QuizQuestionResource extends Resource
{
    protected static ?string $model = QuizQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = 'Quiz Questions';
    protected static ?string $pluralModelLabel = 'Quiz Questions';
    protected static ?string $navigationGroup = 'Assessment Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Question Details')
                ->schema([
                    TextInput::make('question_text')
                        ->label('Question')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Select::make('question_type')
                        ->options([
                            'multiple_choice' => 'Multiple Choice',
                            'true_false' => 'True/False',
                            'short_answer' => 'Short Answer',
                        ])
                        ->required()
                        ->default('multiple_choice')
                        ->reactive(),

                    TextInput::make('points')
                        ->numeric()
                        ->default(1)
                        ->required(),

                    Textarea::make('explanation')
                        ->label('Question Explanation')
                        ->helperText('Provide additional context or explanation for this question')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Answer Options')
                ->schema([
                    Repeater::make('options')
                        ->relationship()
                        ->schema([
                            TextInput::make('option_text')
                                ->label('Option Text')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Forms\Components\Toggle::make('is_correct')
                                ->label('Correct Answer')
                                ->default(false),

                            TextInput::make('score')
                                ->label('Points')
                                ->numeric()
                                ->default(1)
                                ->minValue(0),

                            Select::make('feedback_type')
                                ->label('Feedback Type')
                                ->options([
                                    'positive' => 'Positive',
                                    'negative' => 'Negative',
                                    'neutral' => 'Neutral'
                                ])
                                ->default('neutral'),

                            Textarea::make('explanation')
                                ->label('Option Explanation')
                                ->helperText('Explain why this answer is correct/incorrect')
                                ->columnSpanFull(),
                        ])
                        ->orderColumn('order')
                        ->defaultItems(4)
                        ->addActionLabel('Add Option')
                        ->reorderableWithButtons()
                        ->cloneable()
                        ->columnSpanFull()
                ])
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question_text')
                    ->label('Question')
                    ->searchable()
                    ->wrap()
                    ->limit(50),

                TextColumn::make('question_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'multiple_choice' => 'Multiple Choice',
                        'true_false' => 'True/False',
                        'short_answer' => 'Short Answer',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'multiple_choice',
                        'success' => 'true_false',
                        'warning' => 'short_answer',
                    ]),

                TextColumn::make('points')
                    ->sortable(),

                TextColumn::make('options_count')
                    ->label('Options')
                    ->counts('options')
                    ->sortable(),

                IconColumn::make('has_correct_answer')
                    ->label('Has Correct')
                    ->boolean()
                    ->getStateUsing(fn($record) => $record->options()->where('is_correct', true)->exists())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizQuestions::route('/'),
            'create' => Pages\CreateQuizQuestion::route('/create'),
            'edit' => Pages\EditQuizQuestion::route('/{record}/edit'),
        ];
    }
}
