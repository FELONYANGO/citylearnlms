<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([

                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),

                                Select::make('role')
                                    ->required()
                                    ->options([
                                        'admin' => 'Admin',
                                        'trainer' => 'Trainer',
                                        'student' => 'Student',
                                        'org_rep' => 'Organization Rep',
                                    ]),

                                Select::make('organization_id')
                                    ->label('Organization')
                                    ->relationship('organization', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                Toggle::make('first_login')
                                    ->label('First Login')
                                    ->default(true),

                                DateTimePicker::make('email_verified_at')
                                    ->label('Email Verified At')
                                    ->nullable(),

                            ]),

                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                            ->required(fn(string $context): bool => $context === 'create')
                            ->label('Password')
                            ->maxLength(255),
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
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('organization.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('first_login')
                    ->searchable()

            ])
            ->filters([
                TernaryFilter::make('first_login')
                    ->label('First Login'),
                SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'trainer' => 'Trainer',
                        'student' => 'Student',
                        'org_rep' => 'Organization Rep',
                    ]),

                SelectFilter::make('organization_id')
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('email_verified_at')
                    ->label('Email Verified At'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
