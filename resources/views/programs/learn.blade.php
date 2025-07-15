@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Program Header -->
        <div class="bg-gradient-to-r from-[#2B593F] to-[#1E4230] rounded-2xl shadow-xl p-6 sm:p-8 mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-6">
                <div class="flex-1 w-full lg:w-auto">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $program->title }}</h1>
                        <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-1">
                            <span class="text-white/90">{{ $totalCourses }} Courses</span>
                        </div>
                    </div>

                    @if($program->organization)
                    <div class="flex items-center text-white/80 text-sm mb-6">
                        <i class="fas fa-building mr-2"></i>
                        <span>by {{ $program->organization->name }}</span>
                    </div>
                    @endif

                    <!-- Overall Progress -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 sm:p-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-white font-medium">Overall Progress</span>
                            <span class="text-white font-bold text-lg">{{ number_format($overallProgress, 1) }}%</span>
                        </div>
                        <div class="w-full bg-white/20 rounded-full h-3">
                            <div class="bg-white h-3 rounded-full transition-all duration-300"
                                style="width: {{ $overallProgress }}%"></div>
                        </div>
                        <div class="text-white/80 text-sm mt-3 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ $completedCourses }} of {{ $totalCourses }} courses completed
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 w-full lg:w-auto lg:min-w-[300px]">
                    <h3 class="text-white font-semibold mb-4">Your Progress</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                            <span class="text-white/80">Enrolled</span>
                            <span class="text-white font-medium">{{ $enrollment->enrolled_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                            <span class="text-white/80">Status</span>
                            <span
                                class="inline-flex items-center bg-green-500/20 text-green-300 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-circle text-xs mr-2"></i>
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-white/5 rounded-lg">
                            <span class="text-white/80">Completed</span>
                            <span class="text-white font-medium">{{ $completedCourses }}/{{ $totalCourses }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Levels -->
        <div class="space-y-8">
            @foreach($coursesByLevel as $level => $courses)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <!-- Level Header -->
                <div class="bg-gradient-to-r from-[#2B593F]/5 to-transparent border-b px-6 py-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-[#2B593F] rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ $level }}
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900">
                                    {{ $levelNames[$level] ?? 'Unknown' }}
                                </h2>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $courses->count() }} {{ Str::plural('course', $courses->count()) }} in this level
                            </p>
                        </div>
                        <div class="sm:text-right">
                            @php
                            $levelCompleted = $courses->filter(function($course) {
                            return $course->enrollments->first() &&
                            $course->enrollments->first()->progress_percentage >= 100;
                            })->count();
                            $levelProgress = $courses->count() > 0 ? ($levelCompleted / $courses->count()) * 100 : 0;
                            @endphp
                            <div class="text-sm text-gray-600 mb-2">{{ $levelCompleted }}/{{ $courses->count() }}
                                completed</div>
                            <div class="w-full sm:w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-[#2B593F] h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $levelProgress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Courses Grid -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($courses as $course)
                        @php
                        $courseEnrollment = $course->enrollments->first();
                        $isEnrolled = $courseEnrollment !== null;
                        $progress = $isEnrolled ? $courseEnrollment->progress_percentage : 0;
                        $isCompleted = $progress >= 100;
                        @endphp

                        <div
                            class="bg-white border rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300">
                            <!-- Course Image -->
                            <div class="relative h-48 bg-gray-100">
                                <img src="{{ asset($course->thumbnail ?? 'images/default-course.jpg') }}"
                                    alt="{{ $course->title }}" class="w-full h-full object-cover"
                                    onerror="this.src='{{ asset('images/default-course.jpg') }}'; this.onerror=null;">

                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($isCompleted)
                                    <span
                                        class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium shadow-sm">
                                        <i class="fas fa-check-circle mr-1"></i> Completed
                                    </span>
                                    @elseif($isEnrolled && $progress > 0)
                                    <span
                                        class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-medium shadow-sm">
                                        <i class="fas fa-clock mr-1"></i> In Progress
                                    </span>
                                    @else
                                    <span
                                        class="bg-gray-500 text-white px-3 py-1 rounded-full text-sm font-medium shadow-sm">
                                        <i class="fas fa-circle mr-1"></i> Not Started
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Course Content -->
                            <div class="p-6">
                                <h3 class="font-semibold text-lg text-gray-900 mb-3 line-clamp-2">{{ $course->title }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ strip_tags(Str::limit($course->description, 100)) }}
                                </p>

                                <!-- Course Stats -->
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="flex items-center text-sm text-gray-500 bg-gray-50 rounded-lg p-2">
                                        <i class="fas fa-clock mr-2 text-[#2B593F]"></i>
                                        {{ $course->duration ?? 'Duration not specified' }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500 bg-gray-50 rounded-lg p-2">
                                        <i class="fas fa-book-open mr-2 text-[#2B593F]"></i>
                                        {{ $course->curriculumItems->count() }} {{ Str::plural('lesson',
                                        $course->curriculumItems->count()) }}
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                @if($isEnrolled)
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-600">Progress</span>
                                        <span class="text-sm font-medium text-[#2B593F]">{{ number_format($progress, 1)
                                            }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-[#2B593F] h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                                @endif

                                <!-- Action Button -->
                                <div class="mt-6">
                                    @if($isCompleted)
                                    <a href="{{ route('courses.learn', $course) }}"
                                        class="block w-full text-center bg-green-600 text-white py-3 rounded-xl font-medium hover:bg-green-700 transition-all duration-300 shadow-sm">
                                        <i class="fas fa-redo mr-2"></i> Review Course
                                    </a>
                                    @elseif($isEnrolled)
                                    <a href="{{ route('courses.learn', $course) }}"
                                        class="block w-full text-center bg-[#2B593F] text-white py-3 rounded-xl font-medium hover:bg-[#234732] transition-all duration-300 shadow-sm">
                                        <i class="fas fa-play mr-2"></i> Continue Learning
                                    </a>
                                    @else
                                    <a href="{{ route('courses.learn', $course) }}"
                                        class="block w-full text-center bg-[#2B593F] text-white py-3 rounded-xl font-medium hover:bg-[#234732] transition-all duration-300 shadow-sm">
                                        <i class="fas fa-graduation-cap mr-2"></i> Start Course
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Back to Program -->
        <div class="mt-8 text-center">
            <a href="{{ route('programs.show', $program) }}"
                class="inline-flex items-center px-6 py-3 bg-white text-[#2B593F] rounded-xl font-medium hover:bg-[#2B593F] hover:text-white transition-all duration-300 shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Program Overview
            </a>
        </div>
    </div>
</div>
@endsection