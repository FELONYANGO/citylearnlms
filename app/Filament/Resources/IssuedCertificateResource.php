<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IssuedCertificateResource\Pages;
use App\Models\IssuedCertificate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;

class IssuedCertificateResource extends Resource
{
    protected static ?string $model = IssuedCertificate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Issued Certificates';
    protected static ?string $pluralModelLabel = 'Issued Certificates';
    protected static ?string $navigationGroup = 'Certificate Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                ->relationship('user', 'name')
                ->required()
                ->searchable()
                ->preload(),
            Select::make('certificate_id')
                ->relationship('certificate', 'name')
                ->required()
                ->searchable()
                ->preload(),
            DateTimePicker::make('issued_at')
                ->required(),
            TextInput::make('file_path')
                ->label('File Path')
                ->maxLength(255)
                ->nullable(),
            KeyValue::make('metadata')
                ->label('Metadata')
                ->keyLabel('Key')
                ->valueLabel('Value')
                ->nullable(),
            TextInput::make('verification_code')
                ->label('Verification Code')
                ->maxLength(255)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('User')->searchable()->sortable(),
                TextColumn::make('certificate.name')->label('Certificate')->searchable()->sortable(),
                TextColumn::make('issued_at')->dateTime()->sortable(),
                TextColumn::make('verification_code')->label('Verification Code')->sortable(),
                BadgeColumn::make('file_path')
                    ->label('Status')
                    ->colors([
                        'success' => fn($record) => !empty($record->file_path),
                        'danger' => fn($record) => empty($record->file_path),
                    ])
                    ->formatStateUsing(fn($state) => $state ? 'File Available' : 'No File'),
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
            ->defaultSort('issued_at', 'desc');
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
            'index' => Pages\ListIssuedCertificates::route('/'),
            'create' => Pages\CreateIssuedCertificate::route('/create'),
            'view' => Pages\ViewIssuedCertificate::route('/{record}'),
            'edit' => Pages\EditIssuedCertificate::route('/{record}/edit'),
        ];
    }
}
