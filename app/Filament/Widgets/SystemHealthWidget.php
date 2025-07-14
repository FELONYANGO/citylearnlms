<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\User;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Enrollment;
use App\Models\UserQuizAttempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SystemHealthWidget extends Widget
{
    protected static string $view = 'filament.widgets.system-health';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        // Calculate various health metrics
        $totalUsers = User::count();
        $activeUsersToday = User::whereDate('updated_at', today())->count();
        $userActivityRate = $totalUsers > 0 ? round(($activeUsersToday / $totalUsers) * 100, 1) : 0;

        $totalCourses = Course::count();
        $publishedCourses = Course::where('status', 'published')->count();
        $coursePublishRate = $totalCourses > 0 ? round(($publishedCourses / $totalCourses) * 100, 1) : 0;

        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        $enrollmentCompletionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0;

        $totalQuizAttempts = UserQuizAttempt::count();
        $passedQuizAttempts = UserQuizAttempt::where('status', 'passed')->count();
        $quizPassRate = $totalQuizAttempts > 0 ? round(($passedQuizAttempts / $totalQuizAttempts) * 100, 1) : 0;

        // Recent activity indicators
        $recentEnrollments = Enrollment::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $recentQuizAttempts = UserQuizAttempt::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $recentUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // System health indicators
        $healthIndicators = [
            [
                'name' => 'User Activity',
                'value' => $userActivityRate,
                'description' => $activeUsersToday . ' active today',
                'status' => $userActivityRate >= 20 ? 'healthy' : ($userActivityRate >= 10 ? 'warning' : 'critical'),
                'icon' => 'heroicon-o-users',
            ],
            [
                'name' => 'Course Publishing',
                'value' => $coursePublishRate,
                'description' => $publishedCourses . ' of ' . $totalCourses . ' published',
                'status' => $coursePublishRate >= 80 ? 'healthy' : ($coursePublishRate >= 60 ? 'warning' : 'critical'),
                'icon' => 'heroicon-o-academic-cap',
            ],
            [
                'name' => 'Enrollment Completion',
                'value' => $enrollmentCompletionRate,
                'description' => $completedEnrollments . ' of ' . $totalEnrollments . ' completed',
                'status' => $enrollmentCompletionRate >= 70 ? 'healthy' : ($enrollmentCompletionRate >= 50 ? 'warning' : 'critical'),
                'icon' => 'heroicon-o-check-circle',
            ],
            [
                'name' => 'Quiz Performance',
                'value' => $quizPassRate,
                'description' => $passedQuizAttempts . ' of ' . $totalQuizAttempts . ' passed',
                'status' => $quizPassRate >= 80 ? 'healthy' : ($quizPassRate >= 60 ? 'warning' : 'critical'),
                'icon' => 'heroicon-o-chart-bar',
            ],
        ];

        $recentActivity = [
            ['label' => 'New Users (7 days)', 'value' => $recentUsers, 'icon' => 'heroicon-o-user-plus'],
            ['label' => 'New Enrollments (7 days)', 'value' => $recentEnrollments, 'icon' => 'heroicon-o-book-open'],
            ['label' => 'Quiz Attempts (7 days)', 'value' => $recentQuizAttempts, 'icon' => 'heroicon-o-clipboard-document-check'],
        ];

        return [
            'healthIndicators' => $healthIndicators,
            'recentActivity' => $recentActivity,
        ];
    }
}
