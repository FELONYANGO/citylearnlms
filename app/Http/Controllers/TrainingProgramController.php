<?php

namespace App\Http\Controllers;

use App\Models\TrainingProgram;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrainingProgramController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth')->except(['show', 'showLevel']);
        $this->orderService = $orderService;
    }

    public function show(TrainingProgram $program)
    {
        $program->load([
            'courses' => function ($query) {
                $query->orderBy('pivot_order')->with('enrollments');
            },
            'trainer',
            'organization',
            'enrollments' => function ($query) {
                $query->where('status', 'active');
            },
        ]);

        $userState = [
            'isEnrolled' => false,
            'hasValidPayment' => false,
            'isAdmin' => false,
            'isTrainer' => false,
            'enrollment' => null,
            'order' => null
        ];

        if (Auth::check()) {
            $user = Auth::user();

            // Check enrollment
            $enrollment = $user->enrollments()
                ->where('training_program_id', $program->id)
                ->whereNotIn('status', ['cancelled', 'expired'])
                ->first();

            // Check orders
            $order = Order::whereHas('items', function ($query) use ($program) {
                $query->where('orderable_type', TrainingProgram::class)
                    ->where('orderable_id', $program->id);
            })
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->latest()
                ->first();

            $userState['isEnrolled'] = $enrollment !== null;
            $userState['enrollment'] = $enrollment;
            $userState['hasValidPayment'] = $order !== null || ($enrollment && $enrollment->payment_status === 'completed');
            $userState['isAdmin'] = $user->hasRole('admin');
            $userState['isTrainer'] = $user->id === $program->trainer_id;
            $userState['order'] = $order;
        }

        // Hardcode success rate for now since certificates are not implemented
        $successRate = 95;

        return view('programs.show', compact('program', 'successRate', 'userState'));
    }

    public function showLevel(TrainingProgram $program, $level)
    {
        $courses = $program->courses()->where('level', $level)->get();
        $levelNames = [
            1 => 'Basic',
            2 => 'Intermediate',
            3 => 'Advanced',
            4 => 'Expert'
        ];

        return view('programs.level', compact('program', 'courses', 'level', 'levelNames'));
    }

    public function enroll(Request $request, TrainingProgram $program)
    {
        $user = Auth::user();

        // Check if user is already enrolled (including all statuses except cancelled/expired)
        $existingEnrollment = $user->enrollments()
            ->where('training_program_id', $program->id)
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->first();

        if ($existingEnrollment) {
            if ($existingEnrollment->payment_status === 'completed') {
                return redirect()->route('programs.show', $program)
                    ->with('info', 'You are already enrolled in this program.');
            }

            // Check for existing order
            $existingOrder = Order::whereHas('items', function ($query) use ($program) {
                $query->where('orderable_type', TrainingProgram::class)
                    ->where('orderable_id', $program->id);
            })
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if ($existingOrder) {
                return redirect()->route('payments.process', ['order' => $existingOrder->id])
                    ->with('info', 'Please complete your payment to access the program.');
            }

            // No order exists, create one
            $order = $this->orderService->createOrder($user, $program);
            return redirect()->route('payments.process', ['order' => $order->id])
                ->with('info', 'Please complete your payment to access the program.');
        }

        try {
            if ($program->total_fee <= 0) {
                // Free program - create enrollment directly
                DB::transaction(function () use ($user, $program) {
                    // Create program enrollment
                    $programEnrollment = Enrollment::create([
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
                    ->with('success', 'You have been successfully enrolled in the program and all its courses.');
            } else {
                // Paid program - create enrollment and order
                DB::transaction(function () use ($user, $program) {
                    // Create enrollment for paid program
                    Enrollment::create([
                        'user_id' => $user->id,
                        'training_program_id' => $program->id,
                        'status' => 'pending',
                        'enrolled_at' => now(),
                        'enrollment_source' => 'direct',
                        'payment_status' => 'pending',
                        'paid_amount' => $program->total_fee
                    ]);
                });

                // Create order and redirect to payment
                $order = $this->orderService->createOrder($user, $program);

                return redirect()->route('payments.process', ['order' => $order->id])
                    ->with('success', 'Please complete your payment to access the program.');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle unique constraint violation
            if ($e->errorInfo[1] == 1062) { // MySQL duplicate entry error
                return redirect()->back()
                    ->with('info', 'You are already enrolled in this program.');
            }

            // Handle other database errors
            Log::error('Program enrollment failed', [
                'program_id' => $program->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while enrolling. Please try again.');
        } catch (\Exception $e) {
            Log::error('Program enrollment failed', [
                'program_id' => $program->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while enrolling. Please try again.');
        }
    }

    public function learn(TrainingProgram $program)
    {
        $user = Auth::user();

        // Check if user is enrolled and has valid payment
        $enrollment = $user->enrollments()
            ->where('training_program_id', $program->id)
            ->whereNotIn('status', ['cancelled', 'expired'])
            ->first();

        if (!$enrollment || $enrollment->payment_status !== 'completed') {
            return redirect()->route('programs.show', $program)
                ->with('error', 'You need to complete payment to access this program.');
        }

        // Load program with courses organized by level
        $program->load([
            'courses' => function ($query) use ($user) {
                $query->orderBy('level')->orderBy('pivot_order')
                    ->with(['curriculumItems', 'enrollments' => function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    }]);
            },
            'trainer',
            'organization'
        ]);

        // Group courses by level
        $coursesByLevel = $program->courses->groupBy('level');

        // Level names mapping
        $levelNames = [
            1 => 'Basic',
            2 => 'Intermediate',
            3 => 'Advanced',
            4 => 'Expert'
        ];

        // Calculate overall progress
        $totalCourses = $program->courses->count();
        $completedCourses = $program->courses->filter(function ($course) {
            return $course->enrollments->first() &&
                $course->enrollments->first()->progress_percentage >= 100;
        })->count();

        $overallProgress = $totalCourses > 0 ? ($completedCourses / $totalCourses) * 100 : 0;

        return view('programs.learn', compact(
            'program',
            'coursesByLevel',
            'levelNames',
            'enrollment',
            'overallProgress',
            'totalCourses',
            'completedCourses'
        ));
    }
}
