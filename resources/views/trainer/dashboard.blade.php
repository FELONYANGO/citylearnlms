@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-semibold mb-6">Trainer Dashboard</h1>

                <!-- My Courses -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">My Courses</h2>
                        <a href="#"
                            class="inline-flex items-center px-4 py-2 bg-[#2B593F] text-white rounded-md hover:bg-[#234732] transition-colors">
                            Create New Course
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse(Auth::user()->courses as $course)
                        <div class="bg-white border rounded-lg overflow-hidden">
                            <div class="p-4">
                                <h3 class="font-semibold text-lg mb-2">{{ $course->title }}</h3>
                                <div class="text-sm text-gray-500 mb-4">
                                    <p>Students: {{ $course->enrollments->count() }}</p>
                                    <p>Status: {{ ucfirst($course->status) }}</p>
                                </div>
                                <div class="mt-4 flex space-x-2">
                                    <a href="{{ route('courses.show', $course) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                        View Course
                                    </a>
                                    <a href="#"
                                        class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-3">
                            <p class="text-gray-500 text-center py-4">You haven't created any courses yet.</p>
                            <div class="text-center">
                                <a href="#"
                                    class="inline-flex items-center px-4 py-2 bg-[#2B593F] text-white rounded-md hover:bg-[#234732] transition-colors">
                                    Create Your First Course
                                </a>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Student Progress -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Student Progress</h2>
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Course</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Progress</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Last Active</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach(Auth::user()->courses as $course)
                                    @foreach($course->enrollments as $enrollment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $enrollment->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $course->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 w-24">
                                                    <div class="bg-green-600 h-2.5 rounded-full"
                                                        style="width: {{ $enrollment->progress_percentage }}%"></div>
                                                </div>
                                                <span>{{ number_format($enrollment->progress_percentage, 1) }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{
                                            $enrollment->last_accessed_at?->diffForHumans() ?? 'Never' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="#" class="text-blue-600 hover:text-blue-900">View Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection