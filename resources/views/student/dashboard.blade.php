@extends('layouts.app')

@section('content')
<main class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Student Dashboard</h1>
            <p class="mt-2 text-sm text-gray-600">Welcome back, {{ Auth::user()->name }}</p>
        </div>

        <!-- Enrolled Courses Section -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">My Courses</h2>
                    <a href="{{ route('home') }}" class="text-sm text-[#2B593F] hover:text-[#234732] font-medium">
                        Browse All Courses
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse(Auth::user()->enrollments as $enrollment)
                    @if($enrollment->course)
                    <div
                        class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="p-5">
                            <h3 class="font-semibold text-lg text-gray-900 mb-3">{{ $enrollment->course->title ??
                                'Course Title Not Available' }}</h3>
                            <div class="space-y-3">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z" />
                                    </svg>
                                    <span>Enrolled: {{ $enrollment->enrolled_at?->format('M d, Y') ?? 'Date not
                                        available' }}</span>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Progress</span>
                                        <span class="font-medium text-[#2B593F]">{{
                                            number_format($enrollment->progress_percentage ?? 0, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-[#2B593F] h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $enrollment->progress_percentage ?? 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <a href="{{ route('courses.learn', $enrollment->course) }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    Continue Learning
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <div class="col-span-full">
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No courses yet</h3>
                            <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">You haven't enrolled in any courses
                                yet. Browse our catalog to find courses that interest you.</p>
                            <div class="mt-6">
                                <a href="{{ route('home') }}"
                                    class="inline-flex items-center px-6 py-3 bg-[#2B593F] text-white rounded-md hover:bg-[#234732] transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Browse Courses
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>
@endsection