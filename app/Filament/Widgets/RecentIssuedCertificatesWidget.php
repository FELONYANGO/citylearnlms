<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\IssuedCertificate;

class RecentIssuedCertificatesWidget extends Widget
{
    protected static string $view = 'filament.widgets.recent-issued-certificates';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 2;

    protected function getViewData(): array
    {
        $recentIssued = IssuedCertificate::with(['user', 'certificate'])
            ->latest('issued_at')
            ->take(5)
            ->get();

        return [
            'recentIssued' => $recentIssued,
        ];
    }
}
