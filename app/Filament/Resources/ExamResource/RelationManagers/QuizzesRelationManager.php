<?php

namespace App\Filament\Resources\ExamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;

class QuizzesRelationManager extends RelationManager
{
    protected static string $relationship = 'quizzes';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Quiz Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('description')
                            ->maxLength(255),

                        TextInput::make('instructions')
                            ->maxLength(1000),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('time_limit')
                                    ->numeric()
                                    ->suffix('minutes')
                                    ->nullable(),

                                TextInput::make('passing_score')
                                    ->numeric()
                                    ->suffix('%')
                                    ->default(70)
                                    ->required(),

                                TextInput::make('max_attempts')
                                    ->numeric()
                                    ->default(3)
                                    ->required(),

                                TextInput::make('weight')
                                    ->numeric()
                                    ->default(100)
                                    ->suffix('%')
                                    ->required()
                                    ->helperText('Weight of this quiz in the overall exam score'),

                                Toggle::make('randomize_questions')
                                    ->default(true)
                                    ->inline(false),

                                Toggle::make('show_feedback')
                                    ->default(true)
                                    ->inline(false),

                                Toggle::make('is_practice')
                                    ->default(false)
                                    ->inline(false),

                                Toggle::make('is_final')
                                    ->default(false)
                                    ->inline(false),

                                Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'archived' => 'Archived',
                                    ])
                                    ->default('draft')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Quiz Questions')
                    ->schema([
                        Repeater::make('questions')
                            ->relationship()
                            ->schema([
                                TextInput::make('question_text')
                                    ->label('Question')
                                    ->required()
                                    ->maxLength(1000)
                                    ->columnSpanFull(),

                                Select::make('question_type')
                                    ->options([
                                        'multiple_choice' => 'Multiple Choice',
                                        'true_false' => 'True/False',
                                        'short_answer' => 'Short Answer',
                                    ])
                                    ->required()
                                    ->default('multiple_choice')
                                    ->reactive()
                                    ->columnSpanFull(),

                                TextInput::make('points')
                                    ->numeric()
                                    ->default(1)
                                    ->required(),

                                Textarea::make('explanation')
                                    ->label('Question Explanation')
                                    ->maxLength(1000)
                                    ->columnSpanFull(),

                                Repeater::make('options')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('option_text')
                    ->required()
                    ->maxLength(255),

                                        Toggle::make('is_correct')
                                            ->label('Correct Answer')
                                            ->default(false),

                                        Textarea::make('explanation')
                                            ->label('Option Explanation')
                                            ->maxLength(500),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(4)
                                    ->minItems(2)
                                    ->maxItems(6)
                                    ->hidden(fn(callable $get) => $get('../question_type') === 'short_answer')
                                    ->columnSpanFull(),

                                TextInput::make('correct_answer')
                                    ->label('Correct Answer')
                                    ->required()
                                    ->hidden(fn(callable $get) => $get('../question_type') !== 'short_answer')
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(1)
                            ->collapsible()
                            ->cloneable()
                            ->reorderableWithButtons()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('time_limit')
                    ->suffix(' minutes')
                    ->sortable(),

                TextColumn::make('passing_score')
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('max_attempts')
                    ->sortable(),

                TextColumn::make('weight')
                    ->suffix('%')
                    ->sortable(),

                ToggleColumn::make('is_practice')
                    ->label('Practice'),

                ToggleColumn::make('is_final')
                    ->label('Final'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'danger',
                    }),

                TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label('Questions'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create New Quiz')
                    ->modalHeading('Create New Quiz')
                    ->modalDescription('Create a new quiz from scratch'),

                Tables\Actions\AttachAction::make()
                    ->label('Attach Existing Quiz')
                    ->modalHeading('Attach Existing Quiz')
                    ->modalDescription('Select an existing quiz to attach to this exam')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['title'])
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Select Quiz')
                            ->helperText('Choose an existing quiz to attach'),
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->helperText('Set the order in which this quiz should appear'),
                        Forms\Components\TextInput::make('weight')
                            ->numeric()
                            ->default(100)
                            ->suffix('%')
                            ->required()
                            ->helperText('Weight of this quiz in the overall exam score'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order');
    }
}
