<?php

namespace App\Filament\Resources\CurriculumItemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;

class CourseRelationManager extends RelationManager
{
    protected static string $relationship = 'course';

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255),

            TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),

            Textarea::make('description')
                ->required()
                ->rows(3),

            Select::make('type')
                ->options([
                    'file' => 'File Based',
                    'video' => 'Video Based',
                    'blended' => 'Blended Learning',
                    'live' => 'Live Sessions',
                ])
                ->required(),

            Select::make('level')
                ->options([
                    1 => 'Beginner',
                    2 => 'Intermediate',
                    3 => 'Advanced',
                    4 => 'Expert',
                ])
                ->required(),

            TextInput::make('price')
                ->numeric()
                ->prefix('$')
                ->default(0),

            FileUpload::make('thumbnail')
                ->image()
                ->directory('course-thumbnails'),

            TagsInput::make('prerequisites')
                ->placeholder('Add prerequisites')
                ->helperText('Press Enter to add'),

            TagsInput::make('objectives')
                ->placeholder('Add learning objectives')
                ->helperText('Press Enter to add'),

            Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ])
                ->required()
                ->default('draft'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('thumbnail')
                    ->circular()
                    ->defaultImageUrl(
                        fn($record) =>
                        $record->thumbnail ?? asset('images/default-course.png')
                    ),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(40),

                BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'file',
                        'success' => 'video',
                        'warning' => 'blended',
                        'danger' => 'live',
                    ]),

                BadgeColumn::make('level')
                    ->colors([
                        'gray' => fn($state) => $state === 1,
                        'info' => fn($state) => $state === 2,
                        'warning' => fn($state) => $state === 3,
                        'danger' => fn($state) => $state === 4,
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => 'Beginner',
                        2 => 'Intermediate',
                        3 => 'Advanced',
                        4 => 'Expert',
                        default => 'Unknown'
                    }),

                TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'draft',
                        'success' => 'published',
                        'gray' => 'archived',
                    ]),

                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
