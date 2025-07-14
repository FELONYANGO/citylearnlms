<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Financial Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Payments';

    protected static ?string $pluralModelLabel = 'Payments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Details')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->label('Order')
                            ->relationship('order', 'id')
                            ->getOptionLabelFromRecordUsing(fn(Order $record): string => "Order #{$record->id} - {$record->user->name}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn(string $operation): bool => $operation === 'edit'),

                        Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->required()
                            ->prefix('KES')
                            ->disabled(fn(string $operation): bool => $operation === 'edit'),

                        Forms\Components\Select::make('currency')
                            ->label('Currency')
                            ->options([
                                'KES' => 'KES - Kenyan Shilling',
                                'USD' => 'USD - US Dollar',
                                'EUR' => 'EUR - Euro',
                            ])
                            ->default('KES')
                            ->required()
                            ->disabled(fn(string $operation): bool => $operation === 'edit'),

                        Forms\Components\Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'mpesa' => 'M-Pesa',
                                'card' => 'Credit/Debit Card',
                            ])
                            ->default('mpesa')
                            ->required()
                            ->disabled(fn(string $operation): bool => $operation === 'edit'),

                        Forms\Components\Select::make('status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->required()
                            ->live(),

                        Forms\Components\TextInput::make('phone_number')
                            ->label('Phone Number')
                            ->tel()
                            ->placeholder('+254XXXXXXXXX')
                            ->disabled(fn(string $operation): bool => $operation === 'edit'),

                        Forms\Components\TextInput::make('transaction_reference')
                            ->label('Transaction Reference')
                            ->placeholder('Auto-generated or external reference')
                            ->disabled(fn(string $operation): bool => $operation === 'edit'),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\KeyValue::make('meta_data')
                            ->label('Meta Data')
                            ->helperText('Additional payment information like M-Pesa transaction details')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->disabled(fn(string $operation): bool => $operation === 'edit'),

                        Forms\Components\KeyValue::make('payment_response')
                            ->label('Payment Response')
                            ->helperText('Raw response from payment gateway')
                            ->keyLabel('Field')
                            ->valueLabel('Value')
                            ->disabled(fn(string $operation): bool => $operation === 'edit'),
                    ])->collapsible()->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('order.id')
                    ->label('Order')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn(string $state): string => "Order #{$state}"),

                Tables\Columns\TextColumn::make('order.user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('KES')
                    ->sortable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->selectablePlaceholder(false)
                    ->rules(['required']),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'mpesa' => 'success',
                        'card' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'mpesa' => 'M-Pesa',
                        'card' => 'Card',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Phone')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('transaction_reference')
                    ->label('Reference')
                    ->limit(20)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 20 ? $state : null;
                    })
                    ->copyable()
                    ->copyMessage('Reference copied!')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->multiple(),

                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'mpesa' => 'M-Pesa',
                        'card' => 'Credit/Debit Card',
                    ])
                    ->multiple(),

                Filter::make('amount_range')
                    ->form([
                        Forms\Components\TextInput::make('amount_from')
                            ->label('Amount From')
                            ->numeric()
                            ->step(0.01),
                        Forms\Components\TextInput::make('amount_to')
                            ->label('Amount To')
                            ->numeric()
                            ->step(0.01),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['amount_from'],
                                fn(Builder $query, $amount): Builder => $query->where('amount', '>=', $amount),
                            )
                            ->when(
                                $data['amount_to'],
                                fn(Builder $query, $amount): Builder => $query->where('amount', '<=', $amount),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['amount_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Amount from KES ' . $data['amount_from'])
                                ->removeField('amount_from');
                        }
                        if ($data['amount_to'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Amount to KES ' . $data['amount_to'])
                                ->removeField('amount_to');
                        }
                        return $indicators;
                    }),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Created From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Created from ' . \Carbon\Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Created until ' . \Carbon\Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn(Payment $record): bool => in_array($record->status, ['pending', 'processing'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Are you sure you want to delete these payments? This action cannot be undone.')
                        ->visible(fn(): bool => auth()->user()->hasRole('admin')),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s') // Auto-refresh every 30 seconds to show updated payment statuses
            ->emptyStateHeading('No payments found')
            ->emptyStateDescription('Payments will appear here once customers start making purchases.')
            ->emptyStateIcon('heroicon-o-credit-card');
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
