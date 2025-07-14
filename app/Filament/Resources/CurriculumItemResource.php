<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurriculumItemResource\Pages;
use App\Models\CurriculumItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use App\Filament\Resources\CurriculumItemResource\RelationManagers;

class CurriculumItemResource extends Resource
{
    protected static ?string $model = CurriculumItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Curriculum Items';
    protected static ?string $pluralModelLabel = 'Curriculum Items';
    protected static ?string $navigationGroup = 'Course Management';
    protected static ?int $navigationSort = 4;
    //icon

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Curriculum Item Details')->schema([
                Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Select::make('content_type')
                    ->label('Content Type')
                    ->options([
                        'pdf' => 'PDF',
                        'video' => 'Video',
                        'youtube' => 'YouTube',
                        'image' => 'Image',
                        'slide' => 'Slide',
                        'text' => 'Text',
                        'link' => 'Link',
                    ])
                    ->required()
                    ->live()
                    ->native(false),

                Forms\Components\Group::make()->schema([
                    FileUpload::make('resource_url')
                        ->label('Upload File')
                        ->directory('media-resources')
                        ->visible(fn(Forms\Get $get) => in_array($get('content_type'), ['pdf', 'image', 'slide']))
                        ->helperText('Upload PDF, Image, or Slide file.'),

                    TextInput::make('resource_url')
                        ->label('Link / Video URL')
                        ->placeholder('https://...')
                        ->visible(fn(Forms\Get $get) => in_array($get('content_type'), ['video', 'youtube', 'link']))
                        ->helperText('Paste video, YouTube or external resource link.'),

                    Textarea::make('resource_url')
                        ->label('Text Content')
                        ->rows(6)
                        ->visible(fn(Forms\Get $get) => $get('content_type') === 'text')
                        ->helperText('Add raw text or notes for this resource.'),
                ]),
            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('content_type')
                    ->sortable(),

                TextColumn::make('content')
                    ->label('Content')
                    ->formatStateUsing(function ($record) {
                        return match ($record->content_type) {
                            'file' => $record->file_url ? basename($record->file_url) : 'No file',
                            'video' => $record->video_url ? $record->video_url : 'No video URL',
                            'text' => $record->text_content ? substr($record->text_content, 0, 100) . '...' : 'No content',
                            default => 'Unknown type'
                        };
                    })
                    ->wrap()
                    ->color('gray-800')
                    ->limit(100)
                    ->tooltip(function ($record) {
                        return match ($record->content_type) {
                            'file' => $record->file_url,
                            'video' => $record->video_url,
                            'text' => $record->text_content,
                            default => ''
                        };
                    }),

                TextColumn::make('order')
                    ->sortable(),
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
            RelationManagers\CourseRelationManager::class,
            RelationManagers\MediaResourcesRelationManager::class,
            RelationManagers\QuizzesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCurriculumItems::route('/'),
            'create' => Pages\CreateCurriculumItem::route('/create'),
            'edit' => Pages\EditCurriculumItem::route('/{record}/edit'),
        ];
    }
}
