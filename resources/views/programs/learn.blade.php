@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Program Header -->
        <div class="bg-gradient-to-r from-[#2B593F] to-blue-800 rounded-lg shadow-lg p-8 mb-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-white mb-4">{{ $program->title }}</h1>
                    <div class="flex items-center text-white/80 text-sm mb-4">
                        @if($program->organization)
                        <span class="mr-4">by {{ $program->organization->name }}</span>
                        @endif
                        <span>({{ $totalCourses }} Courses)</span>
                    </div>

                    <!-- Overall Progress -->
                    <div class="bg-white/20 rounded-lg p-4 max-w-md">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-white font-medium">Overall Progress</span>
                            <span class="text-white font-bold">{{ number_format($overallProgress, 1) }}%</span>
                        </div>
                        <div class="w-full bg-white/30 rounded-full h-3">
                            <div class="bg-white h-3 rounded-full transition-all duration-300"
                                 style="width: {{ $overallProgress }}%"></div>
                        </div>
                        <div class="text-white/80 text-sm mt-2">
                            {{ $completedCourses }} of {{ $totalCourses }} courses completed
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg p-6 text-gray-800 ml-6">
                    <h3 class="font-semibold mb-4">Your Progress</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Enrolled:</span>
                            <span class="font-medium">{{ $enrollment->enrolled_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status:</span>
                            <span class="font-medium text-green-600">{{ ucfirst($enrollment->status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Completed:</span>
                            <span class="font-medium">{{ $completedCourses }}/{{ $totalCourses }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Levels -->
        <div class="space-y-8">
            @foreach($coursesByLevel as $level => $courses)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Level Header -->
                <div class="bg-gray-50 border-b px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">
                                Level {{ $level }}: {{ $levelNames[$level] ?? 'Unknown' }}
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $courses->count() }} {{ Str::plural('course', $courses->count()) }} in this level
                            </p>
                        </div>
                        <div class="text-right">
                            @php
                                $levelCompleted = $courses->filter(function($course) {
                                    return $course->enrollments->first() &&
                                           $course->enrollments->first()->progress_percentage >= 100;
                                })->count();
                                $levelProgress = $courses->count() > 0 ? ($levelCompleted / $courses->count()) * 100 : 0;
                            @endphp
                            <div class="text-sm text-gray-600">{{ $levelCompleted }}/{{ $courses->count() }} completed</div>
                            <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
                                <div class="bg-[#2B593F] h-2 rounded-full transition-all duration-300"
                                     style="width: {{ $levelProgress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Courses Grid -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($courses as $course)
                        @php
                            $courseEnrollment = $course->enrollments->first();
                            $isEnrolled = $courseEnrollment !== null;
                            $progress = $isEnrolled ? $courseEnrollment->progress_percentage : 0;
                            $isCompleted = $progress >= 100;
                        @endphp

                        <div class="border rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <!-- Course Image -->
                            <div class="relative h-48 bg-gray-100">
                                <img src="{{ asset($course->thumbnail ?? 'images/default-course.jpg') }}"
                                     alt="{{ $course->title }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.src='{{ asset('images/default-course.jpg') }}'; this.onerror=null;">

                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($isCompleted)
                                        <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            ✓ Completed
                                        </span>
                                    @elseif($isEnrolled && $progress > 0)
                                        <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            In Progress
                                        </span>
                                    @else
                                        <span class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                            Not Started
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Course Content -->
                            <div class="p-4">
                                <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $course->title }}</h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    {{ strip_tags(Str::limit($course->description, 100)) }}
                                </p>

                                <!-- Course Stats -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $course->duration ?? 'Duration not specified' }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        {{ $course->curriculumItems->count() }} {{ Str::plural('lesson', $course->curriculumItems->count()) }}
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                @if($isEnrolled)
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm text-gray-600">Progress</span>
                                        <span class="text-sm font-medium text-[#2B593F]">{{ number_format($progress, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-[#2B593F] h-2 rounded-full transition-all duration-300"
                                             style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                                @endif

                                <!-- Action Button -->
                                <div class="mt-4">
                                    @if($isCompleted)
                                        <a href="{{ route('courses.learn', $course) }}"
                                           class="block w-full text-center bg-green-600 text-white py-2 rounded-lg font-medium hover:bg-green-700 transition-colors">
                                            Review Course
                                        </a>
                                    @elseif($isEnrolled)
                                        <a href="{{ route('courses.learn', $course) }}"
                                           class="block w-full text-center bg-[#2B593F] text-white py-2 rounded-lg font-medium hover:bg-[#234732] transition-colors">
                                            Continue Learning
                                        </a>
                                    @else
                                        <a href="{{ route('courses.learn', $course) }}"
                                           class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                            Start Course
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
               class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Program Overview
            </a>
        </div>
    </div>
</div>
@endsection
