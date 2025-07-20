<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use App\Models\Enrollment;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Enrollment'),

            Action::make('export_enrollments')
                ->label('Export Enrollments')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    // Export functionality can be implemented here
                    return redirect()->back()->with('success', 'Export started');
                }),

            Action::make('enrollment_stats')
                ->label('View Stats')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->modalHeading('Enrollment Statistics')
                ->modalContent(function () {
                    $stats = [
                        'total' => Enrollment::count(),
                        'active' => Enrollment::where('status', Enrollment::STATUS_ACTIVE)->count(),
                        'completed' => Enrollment::where('status', Enrollment::STATUS_COMPLETED)->count(),
                        'pending' => Enrollment::where('status', Enrollment::STATUS_PENDING)->count(),
                        'with_certificates' => Enrollment::where('certificate_issued', true)->count(),
                        'total_revenue' => Enrollment::sum('paid_amount'),
                    ];

                    return view('filament.widgets.enrollment-stats', compact('stats'));
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // You can add widgets here if needed
        ];
    }
}
