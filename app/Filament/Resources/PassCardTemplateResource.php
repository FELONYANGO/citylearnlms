<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PassCardTemplateResource\Pages;
use App\Models\PassCardTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class PassCardTemplateResource extends Resource
{
    protected static ?string $model = PassCardTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pass Card Templates';
    protected static ?string $pluralModelLabel = 'Pass Card Templates';
    protected static ?string $navigationGroup = 'Certificate Management';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Template Details')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ]),

                Textarea::make('html_content')
                    ->label('HTML Content')
                    ->rows(15)
                    ->maxLength(50000)
                    ->helperText('HTML content for the pass card template with placeholders like {{name}}, {{Training_program_name}}, etc.')
                    ->required(),

                KeyValue::make('configuration')
                    ->label('Configuration')
                    ->keyLabel('Setting')
                    ->valueLabel('Value')
                    ->helperText('Template configuration settings')
                    ->nullable(),

                KeyValue::make('placeholders')
                    ->label('Placeholders')
                    ->keyLabel('Placeholder')
                    ->valueLabel('Description')
                    ->helperText('Available placeholders for this template')
                    ->nullable(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->sortable(),

                TextColumn::make('pass_cards_count')
                    ->label('Pass Cards')
                    ->counts('passCards')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListPassCardTemplates::route('/'),
            'create' => Pages\CreatePassCardTemplate::route('/create'),
            'view' => Pages\ViewPassCardTemplate::route('/{record}'),
            'edit' => Pages\EditPassCardTemplate::route('/{record}/edit'),
        ];
    }
}
