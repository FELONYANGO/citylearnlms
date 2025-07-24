<?php

namespace App\Filament\Widgets;

use App\Models\PassCard;
use App\Models\CertificateTemplate;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PassCardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPassCards = PassCard::count();
        $activePassCards = PassCard::where('is_active', true)->count();
        $thisMonthPassCards = PassCard::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $mostUsedTemplate = PassCard::select('template_id', DB::raw('count(*) as total'))
            ->groupBy('template_id')
            ->orderByDesc('total')
            ->first();
        $mostUsedTemplateName = $mostUsedTemplate ? CertificateTemplate::find($mostUsedTemplate->template_id)?->name : 'N/A';
        $mostUsedTemplateCount = $mostUsedTemplate->total ?? 0;

        return [
            Stat::make('Total Pass Cards', $totalPassCards)
                ->description('All time')
                ->descriptionIcon('heroicon-m-identification')
                ->color('success'),

            Stat::make('Active Pass Cards', $activePassCards)
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),

            Stat::make('This Month', $thisMonthPassCards)
                ->description('Created this month')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('Most Used Template', $mostUsedTemplateName)
                ->description($mostUsedTemplateCount . ' times used')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),
        ];
    }
}
