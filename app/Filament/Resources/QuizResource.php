<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers\QuestionsRelationManager;
use App\Filament\Resources\QuizResource\RelationManagers\AttemptsRelationManager;
use App\Models\Quiz;
use App\Models\CurriculumItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Quizzes';
    protected static ?string $pluralModelLabel = 'Quizzes';
    protected static ?string $navigationGroup = 'Assessment Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Quiz Details')->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->maxLength(1000),

                Forms\Components\TextInput::make('instructions')
                    ->label('Instructions for Participants')
                    ->nullable()
                    ->maxLength(1000),

                Forms\Components\Select::make('curriculum_item_id')
                    ->label('Curriculum Item')
                    ->relationship('curriculumItem', 'title')
                    ->nullable()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('time_limit')
                    ->label('Time Limit (minutes)')
                    ->numeric()
                    ->nullable(),

                Forms\Components\TextInput::make('passing_score')
                    ->label('Passing Score (%)')
                    ->numeric()
                    ->nullable(),

                Forms\Components\Toggle::make('is_practice')
                    ->label('Is Practice Quiz')
                    ->default(false),

                Forms\Components\Toggle::make('is_final')
                    ->label('Is Final Exam')
                    ->default(false),

                Forms\Components\Toggle::make('randomize_questions')
                    ->label('Randomize Questions')
                    ->default(false),

                Forms\Components\TextInput::make('max_attempts')
                    ->label('Maximum Attempts')
                    ->numeric()
                    ->nullable(),

                Forms\Components\Select::make('show_feedback')
                    ->label('Show Feedback')
                    ->options([
                        'immediate' => 'Immediately After Each Question',
                        'after_completion' => 'After Quiz Completion',
                        'none' => 'No Feedback',
                    ])
                    ->default('after_completion'),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->default('draft'),
            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('curriculumItem.title')->label('Curriculum Item')->searchable(),
                TextColumn::make('time_limit')->label('Time Limit (min)')->sortable(),
                TextColumn::make('passing_score')->label('Passing Score')->sortable(),
                Tables\Columns\TextColumn::make('status')->colors([
                    'draft' => 'gray',
                    'published' => 'green',
                    'archived' => 'red',
                ])->sortable()
                ->badge(),
                TextColumn::make('is_final')

                    ->badge()
                    ->label('Final?'),
                TextColumn::make('is_practice')

                    ->badge()
                    ->label('Practice?'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
            AttemptsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'view' => Pages\ViewQuiz::route('/{record}'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
