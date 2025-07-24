<?php

namespace App\Filament\Widgets;

use App\Models\IssuedCertificate;
use App\Models\Certificate;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class IssuedCertificateStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalIssued = IssuedCertificate::count();
        $issuedThisMonth = IssuedCertificate::whereMonth('issued_at', now()->month)
            ->whereYear('issued_at', now()->year)
            ->count();
        $uniqueUsers = IssuedCertificate::distinct('user_id')->count('user_id');

        $mostIssued = IssuedCertificate::select('certificate_id', DB::raw('count(*) as total'))
            ->groupBy('certificate_id')
            ->orderByDesc('total')
            ->first();
        $mostIssuedCert = $mostIssued ? Certificate::find($mostIssued->certificate_id)?->name : 'N/A';
        $mostIssuedCount = $mostIssued->total ?? 0;

        return [
            Stat::make('Total Issued Certificates', $totalIssued)
                ->description('All time')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Issued This Month', $issuedThisMonth)
                ->description('Current month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Unique Recipients', $uniqueUsers)
                ->description('Users with certificates')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Most Issued Certificate', $mostIssuedCert)
                ->description($mostIssuedCount . ' issued')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning'),
        ];
    }
}
