<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricingPackageResource\Pages;
use App\Models\PricingPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PricingPackageResource extends Resource
{
    protected static ?string $model = PricingPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Pricing Packages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Basic Plan'),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Brief description of the package'),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->prefix('KES')
                                    ->placeholder('0.00'),

                                Forms\Components\Select::make('currency')
                                    ->options([
                                        'KES' => 'KES (Kenyan Shilling)',
                                        'USD' => 'USD (US Dollar)',
                                        'EUR' => 'EUR (Euro)',
                                    ])
                                    ->default('KES')
                                    ->required(),

                                Forms\Components\Select::make('billing_period')
                                    ->options([
                                        'monthly' => 'Monthly',
                                        'yearly' => 'Yearly',
                                        'lifetime' => 'Lifetime',
                                    ])
                                    ->default('monthly')
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Section::make('Package Features')
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->schema([
                                Forms\Components\TextInput::make('feature')
                                    ->placeholder('e.g., Access to 10 courses')
                                    ->required(),
                            ])
                            ->columns(1)
                            ->addActionLabel('Add Feature')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['feature'] ?? null),
                    ]),

                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('color_theme')
                                    ->options([
                                        'blue' => 'Blue',
                                        'green' => 'Green',
                                        'orange' => 'Orange',
                                        'purple' => 'Purple',
                                        'red' => 'Red',
                                    ])
                                    ->default('blue')
                                    ->required(),

                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->label('Display Order'),
                            ]),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('is_popular')
                                    ->label('Mark as Popular')
                                    ->helperText('Shows "Most Popular" badge'),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Package')
                                    ->helperText('Highlights the package'),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->helperText('Show/hide package'),
                            ]),
                    ]),

                Forms\Components\Section::make('Call to Action')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('button_text')
                                    ->default('Get Started')
                                    ->required()
                                    ->placeholder('e.g., Subscribe Now'),

                                Forms\Components\TextInput::make('button_link')
                                    ->url()
                                    ->placeholder('https://example.com/subscribe')
                                    ->helperText('Optional: Custom link for the button'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('KES')
                    ->sortable(),

                Tables\Columns\TextColumn::make('billing_period')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'monthly' => 'gray',
                        'yearly' => 'warning',
                        'lifetime' => 'success',
                    }),

                Tables\Columns\TextColumn::make('color_theme')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'blue' => 'info',
                        'green' => 'success',
                        'orange' => 'warning',
                        'purple' => 'primary',
                        'red' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\BooleanColumn::make('is_popular')
                    ->label('Popular'),

                Tables\Columns\BooleanColumn::make('is_featured')
                    ->label('Featured'),

                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label('Order'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),

                Tables\Filters\TernaryFilter::make('is_popular')
                    ->label('Popular'),

                Tables\Filters\SelectFilter::make('billing_period')
                    ->options([
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                        'lifetime' => 'Lifetime',
                    ]),

                Tables\Filters\SelectFilter::make('color_theme')
                    ->options([
                        'blue' => 'Blue',
                        'green' => 'Green',
                        'orange' => 'Orange',
                        'purple' => 'Purple',
                        'red' => 'Red',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPricingPackages::route('/'),
            'create' => Pages\CreatePricingPackage::route('/create'),
            'edit' => Pages\EditPricingPackage::route('/{record}/edit'),
        ];
    }
}
