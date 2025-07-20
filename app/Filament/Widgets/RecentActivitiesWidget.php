<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\UserQuizAttempt;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RecentActivitiesWidget extends Widget
{
    protected static string $view = 'filament.widgets.recent-activities';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $activities = collect();

        // Get recent enrollments
        $recentEnrollments = Enrollment::with(['user', 'course', 'trainingProgram'])
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentEnrollments as $enrollment) {
            // Determine what the user enrolled in
            $enrolledIn = null;
            if ($enrollment->course) {
                $enrolledIn = $enrollment->course->title;
            } elseif ($enrollment->trainingProgram) {
                $enrolledIn = $enrollment->trainingProgram->title;
            } else {
                $enrolledIn = 'Unknown Course/Program';
            }

            $activities->push([
                'type' => 'enrollment',
                'icon' => 'heroicon-o-user-plus',
                'color' => 'success',
                'title' => $enrollment->user->name . ' enrolled in ' . $enrolledIn,
                'time' => $enrollment->created_at->diffForHumans(),
                'timestamp' => $enrollment->created_at,
            ]);
        }

        // Get recent quiz attempts
        $recentQuizAttempts = UserQuizAttempt::with(['user', 'quiz'])
            ->latest()
            ->take(5)
            ->get();

        foreach ($recentQuizAttempts as $attempt) {
            $activities->push([
                'type' => 'quiz_attempt',
                'icon' => $attempt->status === 'passed' ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle',
                'color' => $attempt->status === 'passed' ? 'success' : 'danger',
                'title' => $attempt->user->name . ' ' . $attempt->status . ' quiz: ' . $attempt->quiz->title,
                'time' => $attempt->created_at->diffForHumans(),
                'timestamp' => $attempt->created_at,
            ]);
        }

        // Get recent users
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        foreach ($recentUsers as $user) {
            $activities->push([
                'type' => 'user_registration',
                'icon' => 'heroicon-o-user',
                'color' => 'info',
                'title' => $user->name . ' joined the platform',
                'time' => $user->created_at->diffForHumans(),
                'timestamp' => $user->created_at,
            ]);
        }

        // Get completed enrollments
        $completedEnrollments = Enrollment::with(['user', 'course', 'trainingProgram'])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        foreach ($completedEnrollments as $enrollment) {
            // Determine what the user completed
            $completedIn = null;
            if ($enrollment->course) {
                $completedIn = $enrollment->course->title;
            } elseif ($enrollment->trainingProgram) {
                $completedIn = $enrollment->trainingProgram->title;
            } else {
                $completedIn = 'Unknown Course/Program';
            }

            $activities->push([
                'type' => 'course_completion',
                'icon' => 'heroicon-o-trophy',
                'color' => 'warning',
                'title' => $enrollment->user->name . ' completed ' . $completedIn,
                'time' => $enrollment->updated_at->diffForHumans(),
                'timestamp' => $enrollment->updated_at,
            ]);
        }

        // Sort by timestamp and take latest 10
        $sortedActivities = $activities->sortByDesc('timestamp')->take(10)->values();

        return [
            'activities' => $sortedActivities,
        ];
    }
}
