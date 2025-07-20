{{-- Exam Header --}}
<div class="h-16 bg-white shadow-sm flex items-center justify-between px-6 border-b border-gray-200">
    <div class="flex items-center space-x-4 flex-1">
        <div>
            <h1 class="text-xl font-semibold text-gray-800">{{ $exam->title ?? 'Final Exam' }}</h1>
            <div class="flex items-center space-x-2 mt-1">
                <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                    Final Exam
                </div>
                {{-- Progress Indicator --}}
                @if(isset($navigationSequence) && count($navigationSequence) > 0)
                <div class="flex items-center space-x-2">
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        @php
                        $currentPos = 0;
                        foreach ($navigationSequence as $index => $navItem) {
                        if ($navItem['type'] === 'exam' && $navItem['id'] == $exam->id) {
                        $currentPos = $index + 1;
                        break;
                        }
                        }
                        $progressPercentage = ($currentPos / count($navigationSequence)) * 100;
                        @endphp
                        <div class="bg-red-500 h-2 rounded-full transition-all duration-300" id="course-progress-bar"
                            style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <span class="text-xs text-gray-500 whitespace-nowrap" id="course-progress-text">
                        {{ $currentPos }} / {{ count($navigationSequence) }}
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
    {{-- Desktop Sidebar Toggle --}}
    <button @click="sidebarOpen = !sidebarOpen"
        class="hidden lg:block p-2 hover:bg-gray-100 rounded-lg focus:outline-none transition-colors">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x-show="sidebarOpen"
                d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x-show="!sidebarOpen"
                d="M13 5l7 7-7 7M5 5l7 7-7 7" />
        </svg>
    </button>
</div>

{{-- Exam Content --}}
<div class="p-6 bg-gray-50 min-h-screen">
    {{-- Exam Results --}}
    @if(isset($examResult) && is_array($examResult))
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            {{-- Results Header --}}
            <div
                class="p-6 {{ ($examResult['passed'] ?? false) ? 'bg-green-50 border-b border-green-200' : 'bg-red-50 border-b border-red-200' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <h2
                            class="text-2xl font-bold {{ ($examResult['passed'] ?? false) ? 'text-green-900' : 'text-red-900' }} mb-2">
                            Final Exam {{ ($examResult['passed'] ?? false) ? 'Passed!' : 'Failed' }}
                        </h2>
                        <p class="text-lg {{ ($examResult['passed'] ?? false) ? 'text-green-700' : 'text-red-700' }}">
                            Your Score: {{ $examResult['score'] ?? 0 }}%
                            ({{ $examResult['correct_answers'] ?? 0 }}/{{ $examResult['total_questions'] ?? 0 }}
                            correct)
                        </p>
                    </div>
                    <div class="text-right">
                        @if($examResult['passed'] ?? false)
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        @else
                        <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Additional Info --}}
                <div
                    class="mt-4 flex items-center space-x-6 text-sm {{ ($examResult['passed'] ?? false) ? 'text-green-600' : 'text-red-600' }}">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Passing Score: {{ $examResult['passing_score'] ?? 70 }}%</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        <span>Attempt: {{ $examResult['attempt_number'] ?? 1 }}</span>
                    </div>
                </div>
            </div>

            {{-- Results Summary --}}
            <div class="p-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $examResult['correct_answers'] }}</div>
                        <div class="text-sm text-gray-500">Correct Answers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $examResult['total_questions'] -
                            $examResult['correct_answers'] }}</div>
                        <div class="text-sm text-gray-500">Incorrect Answers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $examResult['passing_score'] }}%</div>
                        <div class="text-sm text-gray-500">Passing Score</div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="p-6 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <a href="{{ route('courses.learn', $course) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Back to Course
                    </a>

                    @if($examResult['passed'] ?? false)
                    <a href="{{ route('courses.certificate.download', $course) }}"
                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Download Certificate
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- Exam Form --}}
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            {{-- Exam Info Header --}}
            <div class="bg-gradient-to-r from-red-50 to-pink-50 p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $exam->title ?? 'Final Exam' }}</h2>
                        @if($exam->description)
                        <p class="text-gray-600 text-lg">{{ $exam->description }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        @if($exam->time_limit)
                        <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium mb-2">
                            {{ $exam->time_limit }} minutes
                        </div>
                        @endif
                        @if($exam->passing_score)
                        <div class="text-sm text-gray-600">
                            Passing Score: {{ $exam->passing_score }}%
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Exam Instructions --}}
            <div class="p-6 bg-yellow-50 border-b border-gray-200">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-medium text-yellow-800 mb-1">Final Exam Instructions</h3>
                        <p class="text-yellow-700">This is your final exam. Please answer all questions carefully. You must pass this exam to complete the course and receive your certificate.</p>
                    </div>
                </div>
            </div>

            {{-- Error Messages --}}
            @if($errors->any() || session('error'))
            <div class="p-6 bg-red-50 border-b border-red-200">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-medium text-red-800 mb-1">Error</h3>
                        @if(session('error'))
                        <p class="text-red-700">{{ session('error') }}</p>
                        @endif
                        @if($errors->any())
                        <ul class="text-red-700 list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Exam Form --}}
            <form action="{{ route('courses.exam.submit', ['course' => $course, 'exam' => $exam]) }}" method="POST"
                class="p-6" id="exam-form">
                @csrf
                <div class="space-y-8">
                    @foreach($exam->quizzes as $quizIndex => $quiz)
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quiz {{ $quizIndex + 1 }}: {{ $quiz->title }}</h3>

                        @foreach($quiz->questions as $index => $question)
                        <div class="bg-white rounded-lg p-4 mb-4 border border-gray-200">
                            <div class="flex items-start justify-between mb-4">
                                <h4 class="text-md font-semibold text-gray-900 pr-4">{{ $question->question_text }}</h4>
                                <div
                                    class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap">
                                    {{ $index + 1 }} of {{ $quiz->questions->count() }}
                                </div>
                            </div>

                            <div class="space-y-3">
                                @foreach($question->options as $option)
                                <label
                                    class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 hover:border-blue-300 cursor-pointer transition-all group">
                                    <input type="radio" name="answers[{{ $quiz->id }}][{{ $question->id }}]" value="{{ $option->id }}"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" required>
                                    <span class="text-gray-700 group-hover:text-gray-900 flex-1">{{ $option->option_text
                                        }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>

                {{-- Submit Button --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Make sure to answer all questions before submitting the final exam.
                        </div>
                        <button type="submit" id="submit-exam-btn"
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span id="submit-btn-text">Submit Final Exam</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

{{-- Navigation Footer for Exam --}}
<div class="sticky bottom-0 border-t bg-white p-4 shadow-lg">
    <div class="container mx-auto px-4 flex justify-between items-center">
        @if($previousNav)
        <a href="{{ $previousNav['url'] }}" onclick="trackNavigation(event, '{{ $previousNav['url'] }}', 'previous')"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <div class="text-left">
                <div class="text-xs text-gray-500">Previous</div>
                <div class="truncate max-w-32">{{ $previousNav['title'] }}</div>
            </div>
        </a>
        @else
        <div></div>
        @endif

        <div class="flex items-center space-x-3">
            @if($nextNav)
            <a href="{{ $nextNav['url'] }}" onclick="completeCurrentItemAndNavigate(event, '{{ $nextNav['url'] }}')"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                <div class="text-right mr-2">
                    <div class="text-xs text-green-100">Next</div>
                    <div class="truncate max-w-32">{{ $nextNav['title'] }}</div>
                </div>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            @else
            {{-- Complete Course Button - Show when no next item --}}
            @php
            $totalItems = count($navigationSequence);
            $completedItems = count($completions);
            $totalQuizzes = $curriculumItems->whereNotNull('quiz')->count();
            $completedQuizzes = $quizAttempts->where('status', 'completed')->count();
            $allCompleted = ($completedItems + $completedQuizzes) >= ($totalItems);
            @endphp

            @if($allCompleted)
            <a href="{{ route('courses.certificate.download', $course) }}"
                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-left">
                    <div class="text-xs text-blue-100">Congratulations!</div>
                    <div>Complete Course</div>
                </div>
            </a>
            @else
            <button onclick="showCompletionReminder()"
                class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-500 bg-gray-100 cursor-not-allowed">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-left">
                    <div class="text-xs text-gray-400">Complete all items</div>
                    <div>Finish Course</div>
                </div>
            </button>
            @endif
            @endif
        </div>
    </div>
</div>

{{-- Add navigation-based progress tracking script for exam --}}
<script>
    // Initialize navigation tracking for exam (if not already defined)
if (typeof initializeNavigationTracking === 'undefined') {
    let currentItemId = null;
    let currentExamId = null;

    function initializeNavigationTracking() {
        const urlParams = new URLSearchParams(window.location.search);
        currentItemId = urlParams.get('item');
        currentExamId = urlParams.get('exam') || '{{ $exam->id }}';

        if (currentItemId || currentExamId) {
            trackProgress('page_view');
        }
    }

    function completeCurrentItemAndNavigate(event, nextUrl) {
        event.preventDefault();

        // For exams, we don't auto-complete on navigation
        // Exams should be completed via submission
        // Just navigate to the next item
        window.location.href = nextUrl;
    }

    function trackNavigation(event, url, direction) {
        trackProgress(`navigate_${direction}`);
        // Let navigation proceed normally
    }

    function trackProgress(action = 'view') {
        return new Promise((resolve, reject) => {
            if (!currentItemId && !currentExamId) {
                resolve();
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/courses/{{ $course->id }}/track-progress`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    current_item: currentItemId,
                    current_exam: currentExamId,
                    action: action,
                    time_spent: 0
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateProgressUI(data.progress);
                    resolve(data);
                } else {
                    reject(new Error(data.message || 'Failed to track progress'));
                }
            })
            .catch(error => {
                console.error('Progress tracking error:', error);
                reject(error);
            });
        });
    }

    function updateProgressUI(progress) {
        const progressBar = document.getElementById('course-progress-bar');
        if (progressBar) {
            progressBar.style.width = `${progress.progress_percentage}%`;
        }

        const progressText = document.getElementById('course-progress-text');
        if (progressText) {
            progressText.textContent = `${progress.completed_progress} / ${progress.total_progress}`;
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeNavigationTracking();
    });
}

// Exam form validation and debugging
document.addEventListener('DOMContentLoaded', function() {
    const examForm = document.getElementById('exam-form');
    const submitBtn = document.getElementById('submit-exam-btn');
    const submitBtnText = document.getElementById('submit-btn-text');

    if (examForm && submitBtn) {
        console.log('Exam form elements found');

        // Add form submission handler
        examForm.addEventListener('submit', function(e) {
            console.log('Exam form submission started');

            // Prevent double submission
            if (submitBtn.disabled) {
                console.log('Form already being submitted');
                e.preventDefault();
                return false;
            }

            // Validate all questions are answered
            const questions = examForm.querySelectorAll('input[type="radio"]');
            const questionGroups = {};

            questions.forEach(radio => {
                const name = radio.name;
                if (!questionGroups[name]) {
                    questionGroups[name] = [];
                }
                questionGroups[name].push(radio);
            });

            let allAnswered = true;
            let unansweredQuestions = [];

            Object.keys(questionGroups).forEach(groupName => {
                const group = questionGroups[groupName];
                const answered = group.some(radio => radio.checked);
                if (!answered) {
                    allAnswered = false;
                    unansweredQuestions.push(groupName);
                }
            });

            if (!allAnswered) {
                console.log('Not all questions answered:', unansweredQuestions);
                e.preventDefault();
                alert('Please answer all questions before submitting the final exam.');
                return false;
            }

            console.log('All questions answered, proceeding with submission');

            // Disable submit button and show loading state
            submitBtn.disabled = true;
            submitBtnText.textContent = 'Submitting...';
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            // Log form data for debugging
            const formData = new FormData(examForm);
            console.log('Form data being submitted:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            // Let the form submit normally
            return true;
        });

        // Add click handler for additional debugging
        submitBtn.addEventListener('click', function(e) {
            console.log('Submit button clicked');
        });
    } else {
        console.error('Exam form elements not found');
    }
});
</script>
