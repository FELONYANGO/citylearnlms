<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Resources\OrganizationResource\RelationManagers;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;


class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Grid::make(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Organization Name'),

                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->label('Email Address'),

                    TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->maxLength(20)
                        ->label('Phone Number'),

                    TextInput::make('address')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),

            FileUpload::make('logo')
                ->directory('organizations/logos')
                ->image()
                ->imageEditor()
                ->label('Logo'),

            FileUpload::make('cover_image')
                ->directory('organizations/covers')
                ->image()
                ->imageEditor()
                ->label('Cover Image'),

            Textarea::make('more_info')
                ->rows(4)
                ->maxLength(65535)
                ->label('Additional Info'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
