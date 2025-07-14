<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\UserQuizAttempt;
use App\Models\Organization;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Calculate total users
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Calculate total courses
        $totalCourses = Course::count();
        $publishedCourses = Course::where('status', 'published')->count();

        // Calculate total enrollments
        $totalEnrollments = Enrollment::count();
        $activeEnrollments = Enrollment::where('status', 'active')->count();

        // Calculate completion rate
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0;

        // Calculate quiz performance
        $totalQuizzes = Quiz::count();
        $quizAttempts = UserQuizAttempt::count();
        $passedAttempts = UserQuizAttempt::where('status', 'passed')->count();
        $passRate = $quizAttempts > 0 ? round(($passedAttempts / $quizAttempts) * 100, 1) : 0;

        // Calculate organizations
        $totalOrganizations = Organization::count();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description($newUsersThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Active Courses', $publishedCourses)
                ->description($totalCourses . ' total courses')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info')
                ->chart([3, 5, 2, 7, 4, 6, 5, 8]),

            Stat::make('Total Enrollments', $totalEnrollments)
                ->description($activeEnrollments . ' currently active')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('warning')
                ->chart([2, 4, 3, 6, 5, 4, 7, 6]),

            Stat::make('Completion Rate', $completionRate . '%')
                ->description($completedEnrollments . ' completed courses')
                ->descriptionIcon($completionRate >= 70 ? 'heroicon-m-trophy' : 'heroicon-m-chart-bar')
                ->color($completionRate >= 70 ? 'success' : 'danger')
                ->chart([4, 6, 5, 8, 7, 6, 9, 8]),

            Stat::make('Quiz Pass Rate', $passRate . '%')
                ->description($passedAttempts . ' of ' . $quizAttempts . ' attempts')
                ->descriptionIcon($passRate >= 80 ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                ->color($passRate >= 80 ? 'success' : 'danger')
                ->chart([6, 4, 7, 5, 8, 6, 7, 9]),

            Stat::make('Organizations', $totalOrganizations)
                ->description('Partner organizations')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('gray')
                ->chart([1, 2, 1, 3, 2, 3, 4, 3]),
        ];
    }
}
