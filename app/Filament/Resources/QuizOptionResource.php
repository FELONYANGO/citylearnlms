<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizOptionResource\Pages;
use App\Models\QuizOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class QuizOptionResource extends Resource
{
    protected static ?string $model = QuizOption::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Quiz Options';
    protected static ?string $pluralModelLabel = 'Quiz Options';
    protected static ?string $navigationGroup = 'Assessment Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Option Details')
                    ->schema([
                        Forms\Components\Select::make('quiz_question_id')
                            ->label('Quiz Question')
                            ->relationship('question', 'question_text')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('option_text')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Toggle::make('is_correct')
                            ->label('Is Correct?')
                            ->default(false),

                        Forms\Components\Textarea::make('explanation')
                            ->label('Explanation (optional)')
                            ->maxLength(500)
                            ->nullable(),

                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('quiz_question_id')
            ->columns([
                TextColumn::make('question.question_text')
                    ->label('Question')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('option_text')
                    ->label('Option')
                    ->searchable(),

                IconColumn::make('is_correct')
                    ->label('Correct?')
                    ->boolean(),

                TextColumn::make('order')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizOptions::route('/'),
            'create' => Pages\CreateQuizOption::route('/create'),
            'view' => Pages\ViewQuizOption::route('/{record}'),
            'edit' => Pages\EditQuizOption::route('/{record}/edit'),
        ];
    }
}
