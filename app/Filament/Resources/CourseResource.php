<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use App\Models\Category;
use App\Models\TrainingProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Resources\CourseResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TagsInput;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Courses';
    protected static ?string $pluralModelLabel = 'Courses';
    protected static ?string $navigationGroup = 'Course Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Information')->schema([
                Grid::make(2)->schema([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set, callable $get, $livewire) {
                            // Only auto-generate if slug is empty or matches the current title
                            $currentSlug = $get('slug');
                            if (empty($currentSlug) || $currentSlug === Str::slug($get('title'))) {
                                $slug = Str::slug($state);
                                $originalSlug = $slug;
                                $counter = 1;

                                // Get current record ID for edit operations
                                $recordId = $livewire->record?->id;

                                // Check for uniqueness (exclude current record if editing)
                                while (Course::where('slug', $slug)
                                    ->when($recordId, fn($q) => $q->where('id', '!=', $recordId))
                                    ->exists()
                                ) {
                                    $slug = $originalSlug . '-' . $counter++;
                                }

                                $set('slug', $slug);
                            }
                        }),

                    TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Auto-generated from title or enter custom slug')
                        ->alphaDash()
                        ->rules(['regex:/^[a-z0-9-]+$/'])
                        ->validationMessages([
                            'regex' => 'The slug must only contain lowercase letters, numbers, and hyphens.',
                        ]),
                ]),

                RichEditor::make('description')
                    ->required()
                    ->maxLength(25000),

                FileUpload::make('thumbnail')
                    ->image()
                    ->directory('courses/thumbnails')
                    ->label('Thumbnail')
                    ->preserveFilenames()
                    ->disk('public')
                    ->downloadable()
                    ->openable()
                    ->imagePreviewHeight('150'),


            ]),

            Section::make('Details')->schema([
                Grid::make(3)->schema([
                    TextInput::make('price')
                        ->numeric()
                        ->prefix('KES')
                        ->default(0)
                        ->required(),

                    Select::make('type')
                        ->required()
                        ->options([
                            'file' => 'File',
                            'video' => 'Video',
                            'blended' => 'Blended',
                            'live' => 'Live',
                        ])
                        ->native(false),

                    Select::make('level')
                        ->label('Level')
                        ->required()
                        ->options([
                            1 => '1',
                            2 => '2',
                            3 => '3',
                            4 => '4',
                        ])
                        ->default(1)
                        ->native(false),
                ]),

                Select::make('status')
                    ->required()
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ])
                    ->default('draft'),
            ]),

            Section::make('Relationships')->schema([
                Grid::make(2)->schema([
                    Select::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    Select::make('created_by')
                        ->label('Created By')
                        ->relationship('creator', 'name') // assuming you have `creator()` relationship
                        ->required()
                        ->searchable()
                        ->preload(),

                    Select::make('training_program_id')
                        ->label('Training Program')
                        ->options(function () {
                            return ['none' => 'None - Standalone Course'] +
                                \App\Models\TrainingProgram::where('status', 'published')
                                ->orderBy('title')
                                ->pluck('title', 'id')
                                ->toArray();
                        })
                        ->default('none')
                        ->searchable()
                        ->allowHtml()
                        ->getSearchResultsUsing(function (string $search) {
                            $results = ['none' => 'None - Standalone Course'];

                            if (str_contains(strtolower('None - Standalone Course'), strtolower($search))) {
                                // Keep the none option if it matches search
                            } else {
                                unset($results['none']);
                            }

                            $programs = \App\Models\TrainingProgram::where('status', 'published')
                                ->where('title', 'like', "%{$search}%")
                                ->orderBy('title')
                                ->limit(50)
                                ->pluck('title', 'id')
                                ->toArray();

                            return $results + $programs;
                        })
                        ->afterStateHydrated(function ($component, $state) {
                            // Convert NULL to 'none' for form display
                            if ($state === null) {
                                $component->state('none');
                            }
                        })
                        ->helperText('Select "None" for standalone courses or choose a training program'),
                ]),

                Select::make('prerequisites')
                    ->label('Prerequisites')
                    ->multiple()
                    ->options(
                        ['none' => 'None'] + Course::query()
                            ->pluck('title', 'id')
                            ->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText('Select one or more courses as prerequisites or choose None.'),

                TagsInput::make('objectives')
                    ->label('Objectives')
                    ->nullable()
                    ->helperText('List the learning objectives of this course.'),
            ]),

            Section::make('Publishing')->schema([
                DateTimePicker::make('published_at')
                    ->label('Publish Date')
                    ->nullable(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('trainingProgram.title')
                    ->label('Training Program')
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'file' => 'gray',
                        'video' => 'blue',
                        'blended' => 'green',
                        'live' => 'orange',
                    ])
                    ->sortable(),

                BadgeColumn::make('level')
                    ->label('Level')
                    ->colors([
                        1 => 'gray',
                        2 => 'blue',
                        3 => 'yellow',
                        4 => 'red',
                    ])
                    ->sortable(),

                TextColumn::make('price')
                    ->money('KES', true)
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'draft' => 'gray',
                        'published' => 'green',
                        'archived' => 'red',
                    ])
                    ->sortable(),

                TextColumn::make('published_at')
                    ->dateTime()
                    ->label('Published')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->since()
                    ->label('Created'),
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
            RelationManagers\CurriculumItemsRelationManager::class,
            RelationManagers\ExamsRelationManager::class,
            RelationManagers\EnrollmentsRelationManager::class,
            RelationManagers\CertificatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'view' => Pages\ViewCourse::route('/{record}'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
