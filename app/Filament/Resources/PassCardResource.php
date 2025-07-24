<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PassCardResource\Pages;
use App\Models\PassCard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class PassCardResource extends Resource
{
    protected static ?string $model = PassCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationLabel = 'Pass Cards';
    protected static ?string $pluralModelLabel = 'Pass Cards';
    protected static ?string $navigationGroup = 'Certificate Management';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Pass Card Details')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Select::make('training_program_id')
                        ->relationship('trainingProgram', 'title')
                        ->required()
                        ->searchable()
                        ->preload(),
                ]),

                Grid::make(2)->schema([
                    Select::make('template_id')
                        ->label('Pass Card Template')
                        ->relationship('template', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    TextInput::make('license_number')
                        ->label('License Number')
                        ->maxLength(255)
                        ->nullable(),
                ]),

                Grid::make(2)->schema([
                    DateTimePicker::make('issue_date')
                        ->label('Issue Date')
                        ->nullable(),

                    DateTimePicker::make('expiration_date')
                        ->label('Expiration Date')
                        ->nullable(),
                ]),

                Grid::make(2)->schema([
                    TextInput::make('qr_code_data')
                        ->label('QR Code Data')
                        ->maxLength(255)
                        ->nullable()
                        ->helperText('Data to be encoded in QR code'),

                    TextInput::make('qr_code_path')
                        ->label('QR Code Image Path')
                        ->maxLength(255)
                        ->nullable()
                        ->helperText('Path to generated QR code image'),
                ]),

                KeyValue::make('metadata')
                    ->label('Metadata')
                    ->keyLabel('Key')
                    ->valueLabel('Value')
                    ->helperText('Additional data for the pass card template')
                    ->nullable(),

                Textarea::make('notes')
                    ->rows(3)
                    ->maxLength(1000)
                    ->nullable(),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
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

                TextColumn::make('trainingProgram.title')
                    ->label('Training Program')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('license_number')
                    ->label('License Number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('issue_date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('expiration_date')
                    ->dateTime()
                    ->sortable(),

                BadgeColumn::make('is_active')
                    ->label('Status')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListPassCards::route('/'),
            'create' => Pages\CreatePassCard::route('/create'),
            'view' => Pages\ViewPassCard::route('/{record}'),
            'edit' => Pages\EditPassCard::route('/{record}/edit'),
        ];
    }
}
