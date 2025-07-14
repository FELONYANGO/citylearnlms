<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Enrollment;

class VerifyEnrollment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $course = $request->route('course');

        if (!$course) {
            abort(404);
        }

        // Check if user is enrolled and has valid access
        $enrollment = $request->user()->enrollments()
            ->where('course_id', $course->id)
            ->whereNotIn('status', [Enrollment::STATUS_CANCELLED, Enrollment::STATUS_EXPIRED])
            ->first();

        if (!$enrollment) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'You must be enrolled in this course to access its content.');
        }

        if (!$enrollment->hasValidAccess()) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Your access to this course has expired.');
        }

        // Add enrollment to the request for easy access in the controller
        $request->merge(['enrollment' => $enrollment]);

        return $next($request);
    }
}
