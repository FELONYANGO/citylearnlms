<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\CurriculumItem;
use App\Models\UserQuizAttempt;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\CurriculumItemCompletion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class CourseController extends Controller
{
    public function __construct()
    {
        // Show page is public
        $this->middleware('auth')->except(['show']);

        // Additional middleware for learning page to verify enrollment
        $this->middleware('verify.enrollment')->only(['learn', 'showQuiz']);
    }

    public function show(Course $course)
    {
        $userState = [
            'isEnrolled' => false,
            'hasValidPayment' => false,
            'isAdmin' => false,
            'isCreator' => false,
            'enrollment' => null,
            'order' => null
        ];

        if (Auth::check()) {
            $user = Auth::user();

            // Check enrollment
            $enrollment = $user->enrollments()
                ->where('course_id', $course->id)
                ->whereNotIn('status', [Enrollment::STATUS_CANCELLED, Enrollment::STATUS_EXPIRED])
                ->first();

            // Check orders (similar to training program flow)
            $order = Order::whereHas('items', function ($query) use ($course) {
                $query->where('orderable_type', Course::class)
                    ->where('orderable_id', $course->id);
            })
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->latest()
                ->first();

            $userState['isEnrolled'] = $enrollment !== null;
            $userState['enrollment'] = $enrollment;
            $userState['hasValidPayment'] = $order !== null || ($enrollment && $enrollment->payment_status === Enrollment::PAYMENT_COMPLETED);
            $userState['isAdmin'] = $user->hasRole('admin');
            $userState['isCreator'] = $user->id === $course->created_by;
            $userState['order'] = $order;
        }

        return view('courses.show', compact('course', 'userState'));
    }

    public function enroll(Request $request, Course $course)
    {
        $user = Auth::user();

        // Check if user is already enrolled (including all statuses except cancelled/expired)
        $existingEnrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->whereNotIn('status', [Enrollment::STATUS_CANCELLED, Enrollment::STATUS_EXPIRED])
            ->first();

        if ($existingEnrollment) {
            if ($existingEnrollment->payment_status === Enrollment::PAYMENT_COMPLETED) {
                return redirect()->route('courses.learn', $course)
                    ->with('info', 'You are already enrolled in this course.');
            } else {
                return redirect()->route('courses.checkout', $course)
                    ->with('info', 'Please complete your payment to access the course.');
            }
        }

        // Check if user has required role
        if ($course->required_role && !$user->hasRole($course->required_role)) {
            return redirect()->back()
                ->with('error', 'You do not have the required role to enroll in this course.');
        }

        try {
            // Create enrollment with database transaction
            DB::transaction(function () use ($user, $course) {
                $enrollment = new Enrollment([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'status' => $course->price > 0 ? Enrollment::STATUS_PENDING : Enrollment::STATUS_ACTIVE,
                    'enrolled_at' => Carbon::now(),
                    'enrollment_source' => 'direct',
                ]);

                if ($course->price > 0) {
                    // For paid courses
                    $enrollment->paid_amount = $course->price;
                    $enrollment->payment_status = Enrollment::PAYMENT_PENDING;
                } else {
                    // For free courses
                    $enrollment->payment_status = Enrollment::PAYMENT_COMPLETED;
                }

                $enrollment->save();
            });

            // Redirect based on course type
            if ($course->price > 0) {
                return redirect()->route('courses.checkout', $course)
                    ->with('success', 'Please complete your payment to access the course.');
            } else {
                return redirect()->route('courses.learn', $course)
                    ->with('success', 'You have been successfully enrolled in this course.');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle unique constraint violation
            if ($e->errorInfo[1] == 1062) { // MySQL duplicate entry error
                return redirect()->back()
                    ->with('info', 'You are already enrolled in this course.');
            }

            // Handle other database errors
            Log::error('Course enrollment failed', [
                'course_id' => $course->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while enrolling. Please try again.');
        } catch (\Exception $e) {
            Log::error('Course enrollment failed', [
                'course_id' => $course->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while enrolling. Please try again.');
        }
    }

    public function learn(Course $course)
    {
        // Check if user is enrolled
        $enrollment = auth()->user()->enrollments()
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->firstOrFail();

        // Get curriculum items with their quizzes
        $curriculumItems = $course->curriculumItems()
            ->orderBy('order')
            ->with(['mediaResource', 'quiz'])
            ->get();

        // Get completion data for the user
        $completions = CurriculumItemCompletion::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->pluck('curriculum_item_id')
            ->toArray();

        // Get quiz attempts for the user
        $quizAttempts = UserQuizAttempt::where('user_id', auth()->id())
            ->whereIn('quiz_id', $curriculumItems->pluck('quiz.id')->filter())
            ->with('quiz')
            ->get()
            ->keyBy('quiz_id');

        // Get exam if exists
        $exam = $course->exams()->first();

        // Get certificate if available
        $certificate = $course->certificates()
            ->where('user_id', auth()->id())
            ->first();

        // Create navigation sequence (curriculum items + quizzes in order)
        $navigationSequence = [];
        foreach ($curriculumItems as $item) {
            $navigationSequence[] = [
                'type' => 'curriculum_item',
                'id' => $item->id,
                'title' => $item->title,
                'url' => route('courses.learn', ['course' => $course, 'item' => $item->id])
            ];

            // Add quiz if it exists
            if ($item->quiz) {
                $navigationSequence[] = [
                    'type' => 'quiz',
                    'id' => $item->quiz->id,
                    'curriculum_item_id' => $item->id,
                    'title' => $item->quiz->title,
                    'url' => route('courses.quiz', ['course' => $course, 'quiz' => $item->quiz->id])
                ];
            }
        }

        // Get current item or quiz and find navigation position
        $currentItem = null;
        $currentQuiz = null;
        $quizResult = null;
        $currentPosition = 0;
        $previousNav = null;
        $nextNav = null;

        if (request('quiz')) {
            $currentQuiz = Quiz::with('questions.options')->findOrFail(request('quiz'));
            $currentItem = $curriculumItems->firstWhere('id', $currentQuiz->curriculum_item_id);

            // Check if we have quiz result in session
            if (request('completed') && session('quiz_result')) {
                $quizResult = session('quiz_result');
            }

            // Find current position in navigation sequence
            foreach ($navigationSequence as $index => $navItem) {
                if ($navItem['type'] === 'quiz' && $navItem['id'] == $currentQuiz->id) {
                    $currentPosition = $index;
                    break;
                }
            }
        } elseif (request('item')) {
            $currentItem = $curriculumItems->firstWhere('id', request('item'));

            // Find current position in navigation sequence
            foreach ($navigationSequence as $index => $navItem) {
                if ($navItem['type'] === 'curriculum_item' && $navItem['id'] == $currentItem->id) {
                    $currentPosition = $index;
                    break;
                }
            }
        } else {
            $currentItem = $curriculumItems->first();
            $currentPosition = 0;
        }

        // Get previous and next navigation items
        if ($currentPosition > 0) {
            $previousNav = $navigationSequence[$currentPosition - 1];
        }
        if ($currentPosition < count($navigationSequence) - 1) {
            $nextNav = $navigationSequence[$currentPosition + 1];
        }

        return view('courses.learn', compact(
            'course',
            'curriculumItems',
            'exam',
            'certificate',
            'currentItem',
            'currentQuiz',
            'completions',
            'quizAttempts',
            'quizResult',
            'navigationSequence',
            'previousNav',
            'nextNav'
        ));
    }

    public function showQuiz(Course $course, Quiz $quiz)
    {
        // Verify quiz belongs to course
        $curriculumItem = $quiz->curriculumItem;
        abort_if(!$curriculumItem || $curriculumItem->course_id !== $course->id, 404);

        // Get curriculum items for sidebar
        $curriculumItems = $course->curriculumItems()
            ->orderBy('order')
            ->with(['mediaResource', 'quiz'])
            ->get();

        // Get completion data for the user
        $completions = CurriculumItemCompletion::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->pluck('curriculum_item_id')
            ->toArray();

        // Get quiz attempts for the user
        $quizAttempts = UserQuizAttempt::where('user_id', auth()->id())
            ->whereIn('quiz_id', $curriculumItems->pluck('quiz.id')->filter())
            ->with('quiz')
            ->get()
            ->keyBy('quiz_id');

        // Create navigation sequence (curriculum items + quizzes in order)
        $navigationSequence = [];
        foreach ($curriculumItems as $item) {
            $navigationSequence[] = [
                'type' => 'curriculum_item',
                'id' => $item->id,
                'title' => $item->title,
                'url' => route('courses.learn', ['course' => $course, 'item' => $item->id])
            ];

            // Add quiz if it exists
            if ($item->quiz) {
                $navigationSequence[] = [
                    'type' => 'quiz',
                    'id' => $item->quiz->id,
                    'curriculum_item_id' => $item->id,
                    'title' => $item->quiz->title,
                    'url' => route('courses.quiz', ['course' => $course, 'quiz' => $item->quiz->id])
                ];
            }
        }

        $currentItem = $curriculumItem;
        $currentQuiz = $quiz->load('questions.options');
        $quizResult = null;
        $currentPosition = 0;
        $previousNav = null;
        $nextNav = null;

        // Find current position in navigation sequence
        foreach ($navigationSequence as $index => $navItem) {
            if ($navItem['type'] === 'quiz' && $navItem['id'] == $quiz->id) {
                $currentPosition = $index;
                break;
            }
        }

        // Get previous and next navigation items
        if ($currentPosition > 0) {
            $previousNav = $navigationSequence[$currentPosition - 1];
        }
        if ($currentPosition < count($navigationSequence) - 1) {
            $nextNav = $navigationSequence[$currentPosition + 1];
        }

        // Get exam and certificate
        $exam = $course->exams()->first();
        $certificate = $course->certificates()
            ->where('user_id', auth()->id())
            ->first();

        return view('courses.learn', compact(
            'course',
            'curriculumItems',
            'exam',
            'certificate',
            'currentItem',
            'currentQuiz',
            'completions',
            'quizAttempts',
            'quizResult',
            'navigationSequence',
            'previousNav',
            'nextNav'
        ));
    }

    public function checkout(Course $course)
    {
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->where('payment_status', Enrollment::PAYMENT_PENDING)
            ->firstOrFail();

        return view('courses.checkout', compact('course', 'enrollment'));
    }

    public function submitQuiz(Request $request, Course $course, Quiz $quiz)
    {
        try {
            // Verify quiz belongs to course
            $curriculumItem = $quiz->curriculumItem;
            abort_if(!$curriculumItem || $curriculumItem->course_id !== $course->id, 404);

            // Verify user is enrolled
            $enrollment = auth()->user()->enrollments()
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->firstOrFail();

            // Check if user has exceeded max attempts
            if ($quiz->max_attempts) {
                $attemptCount = UserQuizAttempt::where('user_id', auth()->id())
                    ->where('quiz_id', $quiz->id)
                    ->count();

                if ($attemptCount >= $quiz->max_attempts) {
                    return redirect()->back()
                        ->with('error', 'You have exceeded the maximum number of attempts for this quiz.');
                }
            }

            // Validate answers
            $validator = Validator::make($request->all(), [
                'answers' => 'required|array',
                'answers.*' => 'required|exists:quiz_options,id'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please answer all questions.');
            }

            // Load quiz with questions and options
            $quiz->load(['questions.options']);

            // Validate that quiz has questions
            if ($quiz->questions->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'This quiz has no questions. Please contact the administrator.');
            }

            // Calculate score
            $totalQuestions = $quiz->questions->count();
            $correctAnswers = 0;
            $userAnswers = $request->answers;
            $detailedAnswers = [];

            foreach ($quiz->questions as $question) {
                $userAnswerId = $userAnswers[$question->id] ?? null;
                $correctOption = $question->options->where('is_correct', true)->first();
                $userOption = $question->options->where('id', $userAnswerId)->first();

                // Check if question has a correct answer
                if (!$correctOption) {
                    Log::warning('Question has no correct answer', [
                        'question_id' => $question->id,
                        'quiz_id' => $quiz->id
                    ]);
                }

                $isCorrect = $correctOption && $userOption && $correctOption->id == $userOption->id;
                if ($isCorrect) {
                    $correctAnswers++;
                }

                $detailedAnswers[] = [
                    'question_id' => $question->id,
                    'question_text' => $question->question_text,
                    'user_answer_id' => $userAnswerId,
                    'user_answer_text' => $userOption ? $userOption->option_text : null,
                    'correct_answer_id' => $correctOption ? $correctOption->id : null,
                    'correct_answer_text' => $correctOption ? $correctOption->option_text : null,
                    'is_correct' => $isCorrect
                ];
            }

            $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;
            $passed = $quiz->passing_score ? $score >= $quiz->passing_score : $score >= 70;

            // Get next attempt number
            $attemptNumber = UserQuizAttempt::where('user_id', auth()->id())
                ->where('quiz_id', $quiz->id)
                ->max('attempt_number') + 1;

            // Create quiz attempt record
            DB::transaction(function () use ($quiz, $score, $userAnswers, $attemptNumber, $detailedAnswers) {
                UserQuizAttempt::create([
                    'user_id' => auth()->id(),
                    'quiz_id' => $quiz->id,
                    'start_time' => now()->subMinutes(5), // Approximate start time
                    'end_time' => now(),
                    'score' => $score,
                    'status' => 'completed',
                    'answers' => json_encode($detailedAnswers),
                    'attempt_number' => $attemptNumber,
                    'time_spent' => 300 // 5 minutes approximate
                ]);
            });

            // Redirect with results
            return redirect()->route('courses.learn', [
                'course' => $course,
                'quiz' => $quiz->id,
                'completed' => true
            ])->with('quiz_result', [
                'score' => $score,
                'passed' => $passed,
                'correct_answers' => $correctAnswers,
                'total_questions' => $totalQuestions,
                'passing_score' => $quiz->passing_score ?? 70,
                'attempt_number' => $attemptNumber,
                'detailed_answers' => $detailedAnswers,
                'show_feedback' => $quiz->show_feedback !== 'none'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error in quiz submission', [
                'course_id' => $course->id,
                'quiz_id' => $quiz->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A'
            ]);

            return redirect()->back()
                ->with('error', 'A database error occurred while submitting the quiz. Please try again.');
        } catch (\Exception $e) {
            Log::error('Quiz submission failed', [
                'course_id' => $course->id,
                'quiz_id' => $quiz->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'An error occurred while submitting the quiz. Please try again.');
        }
    }

    public function markCurriculumItemComplete(Request $request, Course $course, CurriculumItem $curriculumItem)
    {
        try {
            // Verify curriculum item belongs to course
            abort_if($curriculumItem->course_id !== $course->id, 404);

            // Verify user is enrolled
            $enrollment = auth()->user()->enrollments()
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->firstOrFail();

            // Check if already completed
            $existingCompletion = CurriculumItemCompletion::where('user_id', auth()->id())
                ->where('curriculum_item_id', $curriculumItem->id)
                ->first();

            if ($existingCompletion) {
                return response()->json([
                    'success' => true,
                    'message' => 'Already completed',
                    'completed_at' => $existingCompletion->completed_at->format('Y-m-d H:i:s')
                ]);
            }

            // Create completion record
            $completion = CurriculumItemCompletion::create([
                'user_id' => auth()->id(),
                'curriculum_item_id' => $curriculumItem->id,
                'course_id' => $course->id,
                'completed_at' => now(),
                'time_spent' => $request->input('time_spent', 0),
                'completion_data' => [
                    'content_type' => $curriculumItem->content_type,
                    'completed_via' => 'web',
                    'user_agent' => $request->userAgent()
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Curriculum item marked as completed',
                'completed_at' => $completion->completed_at->format('Y-m-d H:i:s'),
                'completion_percentage' => CurriculumItemCompletion::getCompletionPercentage(auth()->id(), $course->id)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to mark curriculum item as complete', [
                'course_id' => $course->id,
                'curriculum_item_id' => $curriculumItem->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as completed'
            ], 500);
        }
    }

    public function trackProgress(Request $request, Course $course)
    {
        try {
            // Verify user is enrolled
            $enrollment = auth()->user()->enrollments()
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->firstOrFail();

            $currentItem = $request->input('current_item');
            $currentQuiz = $request->input('current_quiz');
            $action = $request->input('action'); // 'page_view', 'complete_and_navigate', 'navigate_previous', etc.

            // Track curriculum item progress
            if ($currentItem) {
                $curriculumItem = CurriculumItem::where('course_id', $course->id)
                    ->where('id', $currentItem)
                    ->firstOrFail();

                // Check if already completed
                $existingCompletion = CurriculumItemCompletion::where('user_id', auth()->id())
                    ->where('curriculum_item_id', $curriculumItem->id)
                    ->first();

                if (!$existingCompletion) {
                    // Complete item based on navigation action
                    $shouldComplete = false;

                    if ($action === 'complete_and_navigate') {
                        // Always complete when Next button is clicked
                        $shouldComplete = true;
                    }

                    if ($shouldComplete) {
                        CurriculumItemCompletion::create([
                            'user_id' => auth()->id(),
                            'curriculum_item_id' => $curriculumItem->id,
                            'course_id' => $course->id,
                            'completed_at' => now(),
                            'time_spent' => 0, // Not tracking time anymore
                            'completion_data' => [
                                'content_type' => $curriculumItem->content_type,
                                'completed_via' => 'navigation',
                                'action' => $action,
                                'user_agent' => $request->userAgent()
                            ]
                        ]);
                    }
                }
            }

            // Handle quiz progress (quizzes are completed via quiz submission, not navigation)
            if ($currentQuiz && $action === 'complete_and_navigate') {
                // For quizzes, we don't auto-complete on navigation
                // They should be completed via quiz submission
                // Just track the navigation for analytics
            }

            // Get updated progress data
            $completions = CurriculumItemCompletion::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->pluck('curriculum_item_id')
                ->toArray();

            $quizAttempts = UserQuizAttempt::where('user_id', auth()->id())
                ->whereIn('quiz_id', $course->curriculumItems()->whereNotNull('quiz_id')->pluck('quiz_id'))
                ->where('status', 'completed')
                ->get()
                ->keyBy('quiz_id');

            // Calculate overall progress
            $totalItems = $course->curriculumItems()->count();
            $totalQuizzes = $course->curriculumItems()->whereNotNull('quiz_id')->count();
            $completedItems = count($completions);
            $completedQuizzes = $quizAttempts->count();

            $totalProgress = $totalItems + $totalQuizzes;
            $completedProgress = $completedItems + $completedQuizzes;
            $progressPercentage = $totalProgress > 0 ? round(($completedProgress / $totalProgress) * 100) : 0;

            return response()->json([
                'success' => true,
                'progress' => [
                    'completed_items' => $completions,
                    'completed_quizzes' => $quizAttempts->pluck('quiz_id')->toArray(),
                    'total_items' => $totalItems,
                    'total_quizzes' => $totalQuizzes,
                    'completed_items_count' => $completedItems,
                    'completed_quizzes_count' => $completedQuizzes,
                    'total_progress' => $totalProgress,
                    'completed_progress' => $completedProgress,
                    'progress_percentage' => $progressPercentage,
                    'is_complete' => $progressPercentage >= 100
                ],
                'message' => $action === 'complete_and_navigate' ? 'Item completed via navigation' : 'Progress tracked',
                'action' => $action
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to track progress', [
                'course_id' => $course->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to track progress'
            ], 500);
        }
    }
}
