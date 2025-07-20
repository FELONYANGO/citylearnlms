<?php

namespace App\Filament\Resources\EnrollmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;

class CurriculumItemCompletionsRelationManager extends RelationManager
{
    protected static string $relationship = 'curriculumItemCompletions';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('curriculum_item_id')
                    ->relationship('curriculumItem', 'title')
                    ->searchable()
                    ->required(),

                Toggle::make('completed')
                    ->label('Completed')
                    ->default(false),

                DateTimePicker::make('completed_at')
                    ->label('Completed At')
                    ->visible(fn(Forms\Get $get) => $get('completed')),

                Forms\Components\TextInput::make('score')
                    ->label('Score')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),

                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('curriculumItem.title')
                    ->label('Curriculum Item')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('curriculumItem.type')
                    ->label('Type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'video' => 'info',
                        'document' => 'warning',
                        'quiz' => 'success',
                        'assignment' => 'danger',
                        default => 'gray',
                    }),

                IconColumn::make('completed')
                    ->label('Completed')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-mark'),

                TextColumn::make('score')
                    ->label('Score')
                    ->suffix('%')
                    ->sortable(),

                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Not completed'),

                TextColumn::make('created_at')
                    ->label('Started At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('completed')
                    ->label('Completion Status')
                    ->options([
                        '1' => 'Completed',
                        '0' => 'Not Completed',
                    ]),

                Tables\Filters\SelectFilter::make('curriculum_item_type')
                    ->label('Item Type')
                    ->options([
                        'video' => 'Video',
                        'document' => 'Document',
                        'quiz' => 'Quiz',
                        'assignment' => 'Assignment',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $value): Builder => $query->whereHas('curriculumItem', function ($q) use ($value) {
                                $q->where('type', $value);
                            }),
                        );
                    }),
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
