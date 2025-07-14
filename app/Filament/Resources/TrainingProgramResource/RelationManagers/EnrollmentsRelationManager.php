<?php

namespace App\Filament\Resources\TrainingProgramResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Model;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    protected static ?string $recordTitleAttribute = 'enrollment_number';

    protected static ?string $title = 'Student Enrollments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Student'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled'
                    ])
                    ->required()
                    ->default('active'),
                Forms\Components\DateTimePicker::make('enrolled_at')
                    ->required()
                    ->default(now()),
                Forms\Components\DateTimePicker::make('completed_at')
                    ->label('Completion Date'),
                Forms\Components\TextInput::make('enrollment_number')
                    ->default(fn() => 'ENR-' . strtoupper(uniqid()))
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->rows(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('enrollment_number')
                    ->searchable()
                    ->copyable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'active',
                        'primary' => 'completed',
                        'danger' => ['expired', 'cancelled'],
                    ]),
                TextColumn::make('enrolled_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('notes')
                    ->limit(30)
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled'
                    ]),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('enrolled_at')
                    ->form([
                        Forms\Components\DatePicker::make('enrolled_from'),
                        Forms\Components\DatePicker::make('enrolled_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['enrolled_from'], fn($q, $date) => $q->whereDate('enrolled_at', '>=', $date))
                            ->when($data['enrolled_until'], fn($q, $date) => $q->whereDate('enrolled_at', '<=', $date));
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Enrollment')
                    ->modalHeading('Create New Enrollment')
                    ->using(function (array $data): Model {
                        $data['enrollment_number'] = 'ENR-' . strtoupper(uniqid());
                        $record = new \App\Models\Enrollment($data);
                        $this->ownerRecord->enrollments()->save($record);
                        return $record;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Enrollment Details'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('enrolled_at', 'desc');
    }
}
