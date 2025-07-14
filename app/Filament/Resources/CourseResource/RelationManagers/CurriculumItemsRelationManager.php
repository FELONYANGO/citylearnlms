<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurriculumItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'CurriculumItems';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('description')->nullable(),
            Forms\Components\Select::make('content_type')
                ->options([
                    'video' => 'Video',
                    'file' => 'File',
                    'text' => 'Text',
                ])->default('text')->required()->reactive(),

            Forms\Components\FileUpload::make('file_url')
                ->label('Upload File')
                ->directory('curriculum/files')
                ->acceptedFileTypes([
                    'application/pdf',
                    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                    'video/mp4', 'video/webm', 'video/ogg',
                    'audio/mp3', 'audio/wav', 'audio/ogg',
                    'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                ])
                ->maxSize(100000) // 100MB
                ->downloadable()
                ->openable()
                ->visible(fn ($get) => $get('content_type') === 'file'),

            Forms\Components\FileUpload::make('video_url')
                ->label('Upload Video')
                ->directory('curriculum/videos')
                ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/avi', 'video/mov'])
                ->maxSize(500000) // 500MB for videos
                ->downloadable()
                ->visible(fn ($get) => $get('content_type') === 'video'),

            Forms\Components\TextInput::make('video_url')
                ->label('Video URL (YouTube or Direct Link)')
                ->url()
                ->placeholder('https://www.youtube.com/watch?v=... or https://example.com/video.mp4')
                ->helperText('Paste a YouTube URL or direct video file link. YouTube links will be automatically embedded.')
                ->visible(fn ($get) => $get('content_type') === 'video'),

            Forms\Components\RichEditor::make('text_content')
                ->label('Text Content')
                ->toolbarButtons([
                    'attachFiles',
                    'blockquote',
                    'bold',
                    'bulletList',
                    'codeBlock',
                    'h2',
                    'h3',
                    'italic',
                    'link',
                    'orderedList',
                    'redo',
                    'strike',
                    'underline',
                    'undo',
                ])
                ->visible(fn ($get) => $get('content_type') === 'text'),

            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(0)
                ->label('Display Order')
                ->helperText('Lower numbers appear first'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('content_type'),
                Tables\Columns\TextColumn::make('order'),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
