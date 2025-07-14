<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingProgramResource\Pages;
use App\Filament\Resources\TrainingProgramResource\RelationManagers;
use App\Models\TrainingProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Str;

class TrainingProgramResource extends Resource
{
    protected static ?string $model = TrainingProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationLabel = 'Training Programs';
    protected static ?string $navigationGroup = 'Course Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live()
                        ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                            if ($operation !== 'create') {
                                return;
                            }

                            $set('slug', Str::slug($state));
                        }),

                    TextInput::make('slug')
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->unique(TrainingProgram::class, 'slug', ignoreRecord: true)
                        ->maxLength(255),

                    Textarea::make('description')
                        ->required()
                        ->rows(3),

                    Select::make('level')
                        ->options([
                            1 => 'Beginner',
                            2 => 'Intermediate',
                            3 => 'Advanced',
                            4 => 'Expert'
                        ])
                        ->required(),

                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required(),
                            Textarea::make('description')
                                ->rows(2),
                        ]),

                    Select::make('trainer_id')
                        ->relationship('trainer', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Trainer'),

                    Select::make('organization_id')
                        ->relationship('organization', 'name')
                        ->searchable()
                        ->preload()
                        //full width
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Program Details')
                ->schema([
                    Textarea::make('audience')
                        ->label('Target Audience')
                        ->required(),

                    TagsInput::make('objectives')
                        ->separator(',')
                        ->required(),

                    TagsInput::make('prerequisites')
                        ->separator(','),

                    TagsInput::make('assessment_method')
                        ->separator(',')
                        ->required(),

                    TextInput::make('duration_days')
                        ->numeric()
                        ->label('Duration (Days)')
                        ->required(),

                    TextInput::make('certification')
                        ->label('Certification Type'),
                ])->columns(2),

            Forms\Components\Section::make('Fees & Dates')
                ->schema([
                    TextInput::make('fee')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),

                    TextInput::make('exam_fee')
                        ->numeric()
                        ->prefix('$')
                        ->default(0),

                    DatePicker::make('start_date'),
                    DatePicker::make('end_date'),

                    Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'archived' => 'Archived'
                        ])
                        ->default('draft')
                        ->required(),

                    Forms\Components\Toggle::make('is_public')
                        ->label('Publicly Visible')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Media')
                ->schema([
                    FileUpload::make('banner')
                        ->image()
                        ->disk('public')
                        ->directory('training-programs/banners')
                        ->visibility('public')
                        ->imageEditor()
                        ->openable()
                        ->downloadable()
                        ->preserveFilenames()
                        ->previewable()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                // ImageColumn::make('banner')
                //     ->circular(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

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

                // TextColumn::make('category.name')
                //     ->sortable(),

                TextColumn::make('trainer.name')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('duration_days')
                    ->label('Duration')
                    ->formatStateUsing(fn($state) => "{$state} days")
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'danger' => 'draft',
                        'success' => 'published',
                        'gray' => 'archived',
                    ]),

                TextColumn::make('start_date')
                    ->date()
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            RelationManagers\CoursesRelationManager::class,
            RelationManagers\CertificatesRelationManager::class,
            RelationManagers\EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingPrograms::route('/'),
            'create' => Pages\CreateTrainingProgram::route('/create'),
            'edit' => Pages\EditTrainingProgram::route('/{record}/edit'),
        ];
    }
}
