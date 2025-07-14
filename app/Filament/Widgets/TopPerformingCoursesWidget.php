<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class TopPerformingCoursesWidget extends Widget
{
    protected static string $view = 'filament.widgets.top-performing-courses';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 2;

    protected function getViewData(): array
    {
        $topCourses = Course::with(['category'])
            ->select('courses.*')
            ->leftJoin('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->groupBy('courses.id')
            ->orderByRaw('COUNT(enrollments.id) DESC')
            ->limit(6)
            ->get()
            ->map(function ($course) {
                $totalEnrollments = Enrollment::where('course_id', $course->id)->count();
                $completedEnrollments = Enrollment::where('course_id', $course->id)
                    ->where('status', 'completed')
                    ->count();

                $completionRate = $totalEnrollments > 0 ?
                    round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0;

                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'category' => $course->category->name ?? 'Uncategorized',
                    'total_enrollments' => $totalEnrollments,
                    'completed_enrollments' => $completedEnrollments,
                    'completion_rate' => $completionRate,
                    'status' => $course->status,
                    'image' => $course->image,
                ];
            });

        return [
            'courses' => $topCourses,
        ];
    }
}
