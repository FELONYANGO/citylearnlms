<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResourceResource\Pages;
use App\Filament\Resources\MediaResourceResource\RelationManagers;
use App\Models\MediaResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;

class MediaResourceResource extends Resource
{
    protected static ?string $model = MediaResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Media Resources';
    protected static ?string $pluralModelLabel = 'Media Resources';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Media Resource Details')->schema([

                    Select::make('curriculum_item_id')
                        ->label('Curriculum Item')
                        ->relationship('curriculumItem', 'title')
                        ->required()
                        ->searchable()
                        ->preload(),


                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),

                    Select::make('resource_type')
                        ->label('Resource Type')
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

                    FileUpload::make('file_url')
                        ->label('Upload File')
                        ->directory('media-resources')
                        ->acceptedFileTypes([
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                        ])
                        ->maxSize(50000)
                        ->visible(fn(Forms\Get $get) => in_array($get('resource_type'), ['pdf', 'image', 'slide']))
                        ->helperText('Upload PDF, Image, or Slide file.'),

                    TextInput::make('video_url')
                        ->label('Video / YouTube URL')
                        ->placeholder('https://…')
                        ->url()
                        ->visible(fn(Forms\Get $get) => in_array($get('resource_type'), ['video', 'youtube']))
                        ->helperText('Provide the URL for a Video or YouTube resource.'),

                    TextInput::make('link_url')
                        ->label('External Link')
                        ->placeholder('https://…')
                        ->url()
                        ->visible(fn(Forms\Get $get) => $get('resource_type') === 'link')
                        ->helperText('Provide a link to external resource.'),

                    Textarea::make('text_content')
                        ->label('Text Content')
                        ->rows(6)
                        ->visible(fn(Forms\Get $get) => $get('resource_type') === 'text')
                        ->helperText('Write the text content for this resource.')
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('curriculum_item_title')
                    ->label('Curriculum Item_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('resource_type'),
                Tables\Columns\TextColumn::make('file_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('video_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('link_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaResources::route('/'),
            'create' => Pages\CreateMediaResource::route('/create'),
            'view' => Pages\ViewMediaResource::route('/{record}'),
            'edit' => Pages\EditMediaResource::route('/{record}/edit'),
        ];
    }
}
