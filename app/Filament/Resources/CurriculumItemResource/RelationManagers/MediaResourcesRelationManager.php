<?php

namespace App\Filament\Resources\CurriculumItemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;

class MediaResourcesRelationManager extends RelationManager
{
    protected static string $relationship = 'mediaResources';

    public function form(Form $form): Form
    {
        return $form->schema([
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

            Forms\Components\Group::make()->schema([
                FileUpload::make('file_url')
                    ->label('Upload File')
                    ->directory('media-resources')
                    ->visible(fn(Forms\Get $get) => in_array($get('resource_type'), ['pdf', 'image', 'slide']))
                    ->helperText('Upload PDF, Image, or Slide file.'),

                TextInput::make('video_url')
                    ->label('Video URL')
                    ->placeholder('https://...')
                    ->visible(fn(Forms\Get $get) => in_array($get('resource_type'), ['video', 'youtube']))
                    ->helperText('Paste video or YouTube link.'),

                TextInput::make('link_url')
                    ->label('External Link')
                    ->placeholder('https://...')
                    ->visible(fn(Forms\Get $get) => $get('resource_type') === 'link')
                    ->helperText('Add external resource link.'),

                Textarea::make('text_content')
                    ->label('Text Content')
                    ->rows(6)
                    ->visible(fn(Forms\Get $get) => $get('resource_type') === 'text')
                    ->helperText('Add text content or notes.'),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                BadgeColumn::make('resource_type')
                    ->label('Type')
                    ->colors([
                        'gray' => 'pdf',
                        'blue' => 'video',
                        'red' => 'youtube',
                        'green' => 'image',
                        'yellow' => 'slide',
                        'cyan' => 'text',
                        'purple' => 'link',
                    ]),

                TextColumn::make('content')
                    ->label('Content')
                    ->formatStateUsing(function ($record) {
                        return match ($record->resource_type) {
                            'pdf', 'image', 'slide' => $record->file_url
                                ? '📄 ' . basename($record->file_url)
                                : '❌ No file',
                            'video', 'youtube' => $record->video_url
                                ? '🎥 ' . $record->video_url
                                : '❌ No video URL',
                            'link' => $record->link_url
                                ? '🔗 ' . $record->link_url
                                : '❌ No link',
                            'text' => $record->text_content
                                ? '📝 ' . substr($record->text_content, 0, 50) . '...'
                                : '❌ Empty',
                            default => '❓ Unknown'
                        };
                    })
                    ->wrap()
                    ->searchable(query: function ($query, $search) {
                        return $query->where(function ($query) use ($search) {
                            $query->where('file_url', 'like', "%{$search}%")
                                ->orWhere('video_url', 'like', "%{$search}%")
                                ->orWhere('link_url', 'like', "%{$search}%")
                                ->orWhere('text_content', 'like', "%{$search}%");
                        });
                    }),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime()
                    ->sortable(),
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
