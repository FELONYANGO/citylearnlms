<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Filament\Resources\EnrollmentResource\RelationManagers;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use App\Models\TrainingProgram;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Carbon\Carbon;
use App\Services\CertificateService;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 4;
    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationBadge(): ?string
    {
        return Enrollment::where('status', Enrollment::STATUS_COMPLETED)
            ->where('certificate_issued', false)
            ->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Enrollment Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->label('User')
                                    ->options(User::pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn() => null),

                                Select::make('course_id')
                                    ->label('Course')
                                    ->options(Course::pluck('title', 'id'))
                                    ->searchable()
                                    ->nullable()
                                    ->live()
                                    ->afterStateUpdated(fn() => null),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('program_id')
                                    ->label('Training Program')
                                    ->options(TrainingProgram::pluck('title', 'id'))
                                    ->searchable()
                                    ->nullable()
                                    ->live()
                                    ->afterStateUpdated(fn() => null),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        Enrollment::STATUS_PENDING => 'Pending',
                                        Enrollment::STATUS_ACTIVE => 'Active',
                                        Enrollment::STATUS_COMPLETED => 'Completed',
                                        Enrollment::STATUS_EXPIRED => 'Expired',
                                        Enrollment::STATUS_SUSPENDED => 'Suspended',
                                        Enrollment::STATUS_CANCELLED => 'Cancelled',
                                    ])
                                    ->default(Enrollment::STATUS_PENDING)
                                    ->required(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('progress_percentage')
                                    ->label('Progress (%)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->default(0)
                                    ->suffix('%'),

                                DateTimePicker::make('enrolled_at')
                                    ->label('Enrollment Date')
                                    ->default(now())
                                    ->required(),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Payment Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('paid_amount')
                                    ->label('Paid Amount')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0),

                                Select::make('payment_status')
                                    ->label('Payment Status')
                                    ->options([
                                        Enrollment::PAYMENT_PENDING => 'Pending',
                                        Enrollment::PAYMENT_COMPLETED => 'Completed',
                                        Enrollment::PAYMENT_REFUNDED => 'Refunded',
                                        Enrollment::PAYMENT_FAILED => 'Failed',
                                    ])
                                    ->default(Enrollment::PAYMENT_PENDING),

                                TextInput::make('payment_method')
                                    ->label('Payment Method')
                                    ->placeholder('e.g., Credit Card, PayPal'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('transaction_id')
                                    ->label('Transaction ID')
                                    ->placeholder('Payment gateway transaction ID'),

                                DateTimePicker::make('payment_date')
                                    ->label('Payment Date'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('discount_amount')
                                    ->label('Discount Amount')
                                    ->numeric()
                                    ->prefix('$')
                                    ->default(0),

                                TextInput::make('coupon_code')
                                    ->label('Coupon Code')
                                    ->placeholder('Applied coupon code'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Access & Certificate')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('access_expires_at')
                                    ->label('Access Expires At')
                                    ->placeholder('Leave empty for unlimited access'),

                                DateTimePicker::make('last_accessed_at')
                                    ->label('Last Accessed At'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Toggle::make('certificate_issued')
                                    ->label('Certificate Issued')
                                    ->default(false),

                                TextInput::make('certificate_number')
                                    ->label('Certificate Number')
                                    ->placeholder('Auto-generated certificate number'),
                            ]),

                        DateTimePicker::make('certificate_issued_at')
                            ->label('Certificate Issued At')
                            ->visible(fn(Forms\Get $get) => $get('certificate_issued')),
                    ])
                    ->collapsible(),

                Section::make('Additional Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('enrollment_source')
                                    ->label('Enrollment Source')
                                    ->placeholder('e.g., direct, referral, promotion'),

                                TextInput::make('referral_code')
                                    ->label('Referral Code')
                                    ->placeholder('Referral code used'),
                            ]),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->placeholder('Admin or system notes')
                            ->rows(3),

                        KeyValue::make('meta_data')
                            ->label('Meta Data')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                TextColumn::make('trainingProgram.title')
                    ->label('Program')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => Enrollment::STATUS_PENDING,
                        'success' => Enrollment::STATUS_ACTIVE,
                        'primary' => Enrollment::STATUS_COMPLETED,
                        'danger' => Enrollment::STATUS_EXPIRED,
                        'secondary' => Enrollment::STATUS_SUSPENDED,
                        'gray' => Enrollment::STATUS_CANCELLED,
                    ]),

                TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->suffix('%')
                    ->sortable()
                    ->color(fn(string $state): string => match (true) {
                        (int) $state >= 100 => 'success',
                        (int) $state >= 75 => 'warning',
                        (int) $state >= 50 => 'info',
                        default => 'danger',
                    }),

                BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'warning' => Enrollment::PAYMENT_PENDING,
                        'success' => Enrollment::PAYMENT_COMPLETED,
                        'danger' => Enrollment::PAYMENT_FAILED,
                        'secondary' => Enrollment::PAYMENT_REFUNDED,
                    ]),

                TextColumn::make('paid_amount')
                    ->label('Amount')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('enrolled_at')
                    ->label('Enrolled')
                    ->dateTime()
                    ->sortable(),

                IconColumn::make('certificate_issued')
                    ->label('Certificate')
                    ->boolean()
                    ->trueIcon('heroicon-o-academic-cap')
                    ->falseIcon('heroicon-o-x-mark'),

                TextColumn::make('last_accessed_at')
                    ->label('Last Access')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Never'),

                TextColumn::make('certificate_number')
                    ->label('Certificate #')
                    ->searchable()
                    ->placeholder('Not issued')
                    ->visible(fn() => true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        Enrollment::STATUS_PENDING => 'Pending',
                        Enrollment::STATUS_ACTIVE => 'Active',
                        Enrollment::STATUS_COMPLETED => 'Completed',
                        Enrollment::STATUS_EXPIRED => 'Expired',
                        Enrollment::STATUS_SUSPENDED => 'Suspended',
                        Enrollment::STATUS_CANCELLED => 'Cancelled',
                    ]),

                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        Enrollment::PAYMENT_PENDING => 'Pending',
                        Enrollment::PAYMENT_COMPLETED => 'Completed',
                        Enrollment::PAYMENT_REFUNDED => 'Refunded',
                        Enrollment::PAYMENT_FAILED => 'Failed',
                    ]),

                Filter::make('enrolled_at')
                    ->label('Enrollment Date')
                    ->form([
                        DatePicker::make('enrolled_from')
                            ->label('From'),
                        DatePicker::make('enrolled_until')
                            ->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['enrolled_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('enrolled_at', '>=', $date),
                            )
                            ->when(
                                $data['enrolled_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('enrolled_at', '<=', $date),
                            );
                    }),

                Filter::make('has_certificate')
                    ->label('Has Certificate')
                    ->query(fn(Builder $query): Builder => $query->where('certificate_issued', true)),

                Filter::make('expired_access')
                    ->label('Expired Access')
                    ->query(fn(Builder $query): Builder => $query->where('access_expires_at', '<', now())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_completed')
                    ->label('Mark Completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Enrollment $record) {
                        $record->markAsCompleted();
                    })
                    ->visible(fn(Enrollment $record) => $record->status !== Enrollment::STATUS_COMPLETED),

                Tables\Actions\Action::make('issue_certificate')
                    ->label('Issue Certificate')
                    ->icon('heroicon-o-academic-cap')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(function (Enrollment $record) {
                        $certificateService = app(CertificateService::class);
                        $certificateService->issueCertificate($record);
                    })
                    ->visible(
                        fn(Enrollment $record) =>
                        $record->status === Enrollment::STATUS_COMPLETED &&
                            !$record->certificate_issued
                    ),

                Tables\Actions\Action::make('preview_certificate')
                    ->label('Preview Certificate')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn(Enrollment $record) =>
                        $record->certificate_issued && $record->course
                            ? route('certificates.preview', $record->course)
                            : '#'
                    )
                    ->openUrlInNewTab()
                    ->visible(fn(Enrollment $record) =>
                        $record->certificate_issued && $record->course
                    ),

                Tables\Actions\Action::make('view_certificate')
                    ->label('View Certificate')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->url(fn(Enrollment $record) =>
                        $record->certificate_issued && $record->course
                            ? route('certificates.show', $record->course)
                            : '#'
                    )
                    ->openUrlInNewTab()
                    ->visible(fn(Enrollment $record) =>
                        $record->certificate_issued && $record->course
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_completed')
                        ->label('Mark as Completed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->markAsCompleted();
                            }
                        }),
                    Tables\Actions\BulkAction::make('mark_active')
                        ->label('Mark as Active')
                        ->icon('heroicon-o-play-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['status' => Enrollment::STATUS_ACTIVE]);
                            }
                        }),

                    Tables\Actions\BulkAction::make('issue_certificates')
                        ->label('Issue Certificates')
                        ->icon('heroicon-o-academic-cap')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalHeading('Issue Certificates')
                        ->modalDescription('This will issue certificates for all selected completed enrollments that don\'t have certificates yet.')
                        ->action(function ($records) {
                            $certificateService = app(CertificateService::class);
                            $issuedCount = 0;

                            foreach ($records as $record) {
                                if ($record->status === Enrollment::STATUS_COMPLETED && !$record->certificate_issued) {
                                    if ($certificateService->issueCertificate($record)) {
                                        $issuedCount++;
                                    }
                                }
                            }

                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Certificates Issued')
                                ->body("Successfully issued {$issuedCount} certificates.")
                                ->send();
                        })
                        ->visible(fn() => true),
                ]),
            ])
            ->defaultSort('enrolled_at', 'desc');
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
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'view' => Pages\ViewEnrollment::route('/{record}'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
