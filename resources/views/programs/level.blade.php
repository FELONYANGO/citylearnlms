@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            {{ $program->title }} - Level {{ $level }} ({{ $levelNames[$level] }})
        </h1>
        <p class="mt-2 text-gray-600">Courses available in this level</p>
    </div>

    @if($courses->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courses as $course)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
            @if($course->image)
            <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
            @else
            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
            @endif

            <div class="p-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-emerald-600">{{ $course->category?->name ?? 'Uncategorized'
                        }}</span>
                    <span class="text-sm text-gray-500">{{ $course->duration_text }}</span>
                </div>

                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $course->title }}</h3>
                <p class="text-gray-600 mb-4 line-clamp-2">{{ $course->description }}</p>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                            </path>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $course->level }}</span>
                    </div>
                    <span class="text-lg font-bold text-emerald-600">{{ $course->formatted_price }}</span>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="text-sm text-gray-500">{{ $course->enrollments->count() }} students</span>
                    </div>
                    <a href="{{ route('courses.show', $course->id) }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        View Course
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No courses available</h3>
        <p class="mt-1 text-sm text-gray-500">No courses have been added to this level yet.</p>
    </div>
    @endif
</div>
@endsection
