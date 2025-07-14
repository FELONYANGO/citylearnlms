<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\TrainingProgram;
use App\Models\Enrollment;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Show checkout page for a course
     */
    public function showCourseCheckout(Course $course)
    {
        $user = Auth::user();

        // Check if user is already enrolled
        $existingEnrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->whereNotIn('status', [Enrollment::STATUS_CANCELLED, Enrollment::STATUS_EXPIRED])
            ->first();

        if ($existingEnrollment) {
            if ($existingEnrollment->payment_status === Enrollment::PAYMENT_COMPLETED) {
                return redirect()->route('courses.learn', $course)
                    ->with('info', 'You are already enrolled in this course.');
            }
            // If payment is pending, continue to checkout
        }

        if ($course->price <= 0) {
            try {
                // Free course - create enrollment directly
                DB::transaction(function () use ($user, $course) {
                    Enrollment::create([
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                        'status' => 'active',
                        'enrolled_at' => now(),
                        'enrollment_source' => 'direct',
                        'payment_status' => 'completed',
                        'paid_amount' => 0
                    ]);
                });

                return redirect()->route('courses.learn', $course)
                    ->with('success', 'You have been enrolled in this course.');
            } catch (\Illuminate\Database\QueryException $e) {
                // Handle unique constraint violation
                if ($e->errorInfo[1] == 1062) {
                    return redirect()->route('courses.learn', $course)
                        ->with('info', 'You are already enrolled in this course.');
                }

                Log::error('Course enrollment failed in checkout', [
                    'course_id' => $course->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);

                return redirect()->back()
                    ->with('error', 'An error occurred while enrolling. Please try again.');
            }
        }

        return view('courses.checkout', [
            'purchasable' => $course,
            'type' => 'course'
        ]);
    }

    /**
     * Show checkout page for a training program
     */
    public function showProgramCheckout(TrainingProgram $program)
    {
        $user = Auth::user();

        // Check if user is already enrolled
        $existingEnrollment = $user->enrollments()
            ->where('training_program_id', $program->id)
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->first();

        if ($existingEnrollment) {
            if ($existingEnrollment->payment_status === 'completed') {
                return redirect()->route('programs.show', $program)
                    ->with('info', 'You are already enrolled in this program.');
            }
            // If payment is pending, continue to checkout
        }

        if ($program->total_fee <= 0) {
            try {
                // Free program - create enrollment directly
                DB::transaction(function () use ($user, $program) {
                    // Create program enrollment
                    Enrollment::create([
                        'user_id' => $user->id,
                        'training_program_id' => $program->id,
                        'status' => 'active',
                        'enrolled_at' => now(),
                        'enrollment_source' => 'direct',
                        'payment_status' => 'completed',
                        'paid_amount' => 0
                    ]);

                    // Enroll in all courses
                    foreach ($program->courses as $course) {
                        // Check if user is already enrolled in this course
                        $existingCourseEnrollment = $user->enrollments()
                            ->where('course_id', $course->id)
                            ->whereNotIn('status', ['cancelled', 'expired'])
                            ->first();

                        if (!$existingCourseEnrollment) {
                            Enrollment::create([
                                'user_id' => $user->id,
                                'course_id' => $course->id,
                                'training_program_id' => $program->id,
                                'status' => 'active',
                                'enrolled_at' => now(),
                                'enrollment_source' => 'program',
                                'payment_status' => 'completed',
                                'paid_amount' => 0
                            ]);
                        }
                    }
                });

                return redirect()->route('programs.show', $program)
                    ->with('success', 'You have been enrolled in this program.');

            } catch (\Illuminate\Database\QueryException $e) {
                // Handle unique constraint violation
                if ($e->errorInfo[1] == 1062) {
                    return redirect()->route('programs.show', $program)
                        ->with('info', 'You are already enrolled in this program.');
                }

                Log::error('Program enrollment failed in checkout', [
                    'program_id' => $program->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);

                return redirect()->back()
                    ->with('error', 'An error occurred while enrolling. Please try again.');
            }
        }

        return view('courses.checkout', [
            'purchasable' => $program,
            'type' => 'program'
        ]);
    }

    /**
     * Process the checkout
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'purchasable_type' => 'required|in:course,program',
            'purchasable_id' => 'required|integer'
        ]);

        // Get the purchasable item
        $purchasable = match ($validated['purchasable_type']) {
            'course' => Course::findOrFail($validated['purchasable_id']),
            'program' => TrainingProgram::findOrFail($validated['purchasable_id']),
            default => abort(400, 'Invalid purchasable type')
        };

        $user = Auth::user();

        // Check for existing enrollment based on type
        if ($validated['purchasable_type'] === 'course') {
            $existingEnrollment = $user->enrollments()
                ->where('course_id', $purchasable->id)
                ->whereNotIn('status', [Enrollment::STATUS_CANCELLED, Enrollment::STATUS_EXPIRED])
                ->first();
        } else {
            $existingEnrollment = $user->enrollments()
                ->where('training_program_id', $purchasable->id)
                ->whereNotIn('status', ['cancelled', 'expired'])
                ->first();
        }

        if ($existingEnrollment && $existingEnrollment->payment_status === 'completed') {
            $route = $validated['purchasable_type'] === 'course'
                ? 'courses.learn'
                : 'programs.show';

            return redirect()->route($route, $purchasable)
                ->with('info', 'You are already enrolled in this ' . $validated['purchasable_type'] . '.');
        }

        try {
            // Create order
            $order = $this->orderService->createOrder(
                user: $user,
                purchasable: $purchasable
            );

            // Redirect to payment processing
            return redirect()->route('payments.process', [
                'order' => $order->id
            ]);
        } catch (\Exception $e) {
            Log::error('Checkout process failed', [
                'purchasable_type' => $validated['purchasable_type'],
                'purchasable_id' => $validated['purchasable_id'],
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred during checkout. Please try again.');
        }
    }
}
