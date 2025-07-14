<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Quiz;
use App\Models\UserQuizAttempt;
use Illuminate\Support\Facades\DB;

class QuizPerformanceWidget extends ChartWidget
{
    protected static ?string $heading = 'Quiz Performance Overview';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 2;

    protected function getData(): array
    {
        // Get quiz performance data
        $quizPerformance = Quiz::with('attempts')
            ->get()
            ->take(5)
            ->map(function ($quiz) {
                $totalAttempts = $quiz->attempts->count();
                $passedAttempts = $quiz->attempts->where('status', 'passed')->count();
                $failedAttempts = $quiz->attempts->where('status', 'failed')->count();

                return [
                    'name' => $quiz->title,
                    'total' => $totalAttempts,
                    'passed' => $passedAttempts,
                    'failed' => $failedAttempts,
                    'pass_rate' => $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100, 1) : 0,
                ];
            })
            ->filter(function ($item) {
                return $item['total'] > 0; // Only include quizzes with attempts
            });

        $labels = $quizPerformance->pluck('name')->toArray();
        $passedData = $quizPerformance->pluck('passed')->toArray();
        $failedData = $quizPerformance->pluck('failed')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Passed',
                    'data' => $passedData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Failed',
                    'data' => $failedData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)',
                    ],
                    'ticks' => [
                        'color' => '#6B7280',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'color' => '#6B7280',
                        'maxRotation' => 45,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'color' => '#6B7280',
                        'usePointStyle' => true,
                    ],
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];
    }
}
