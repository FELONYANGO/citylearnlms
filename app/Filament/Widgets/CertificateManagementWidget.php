<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Services\CertificateService;

class CertificateManagementWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEnrollments = Enrollment::where('status', Enrollment::STATUS_COMPLETED)->count();
        $certificatesIssued = Enrollment::where('certificate_issued', true)->count();
        $pendingCertificates = $totalEnrollments - $certificatesIssued;
        $thisMonthCertificates = Enrollment::where('certificate_issued', true)
            ->whereMonth('certificate_issued_at', now()->month)
            ->count();

        return [
            Stat::make('Total Completed Enrollments', $totalEnrollments)
                ->description('Students who completed courses')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Certificates Issued', $certificatesIssued)
                ->description('Total certificates generated')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Pending Certificates', $pendingCertificates)
                ->description('Awaiting certificate issuance')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('This Month', $thisMonthCertificates)
                ->description('Certificates issued this month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
