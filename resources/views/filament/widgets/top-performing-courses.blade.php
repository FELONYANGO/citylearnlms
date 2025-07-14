<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-star class="w-5 h-5 text-yellow-500" />
                Top Performing Courses
            </div>
        </x-slot>

        <div class="space-y-4">
            @forelse($courses as $course)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-gray-900 mb-1">
                                {{ $course['title'] }}
                            </h3>
                            <p class="text-xs text-gray-500 mb-2">
                                {{ $course['category'] }}
                            </p>

                            <!-- Metrics -->
                            <div class="flex items-center gap-4 text-xs">
                                <div class="flex items-center gap-1">
                                    <x-heroicon-o-users class="w-4 h-4 text-blue-500" />
                                    <span class="text-gray-600">{{ $course['total_enrollments'] }} enrolled</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <x-heroicon-o-check-circle class="w-4 h-4 text-green-500" />
                                    <span class="text-gray-600">{{ $course['completed_enrollments'] }} completed</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900">
                                {{ $course['completion_rate'] }}%
                            </div>
                            <div class="text-xs text-gray-500">completion</div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-3">
                        <div class="flex justify-between items-center text-xs text-gray-600 mb-1">
                            <span>Completion Progress</span>
                            <span>{{ $course['completion_rate'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300
                                @if($course['completion_rate'] >= 80) bg-green-500
                                @elseif($course['completion_rate'] >= 60) bg-yellow-500
                                @else bg-red-500
                                @endif
                            " style="width: {{ $course['completion_rate'] }}%"></div>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="mt-3 flex justify-between items-center">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if($course['status'] === 'published') bg-green-100 text-green-800
                            @elseif($course['status'] === 'draft') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif
                        ">
                            @if($course['status'] === 'published')
                                <x-heroicon-o-eye class="w-3 h-3 mr-1" />
                                Published
                            @elseif($course['status'] === 'draft')
                                <x-heroicon-o-document class="w-3 h-3 mr-1" />
                                Draft
                            @else
                                <x-heroicon-o-archive-box class="w-3 h-3 mr-1" />
                                Archived
                            @endif
                        </span>

                        <div class="flex items-center gap-2">
                            <div class="text-xs text-gray-500">
                                Rank #{{ $loop->iteration }}
                            </div>
                            @if($loop->iteration <= 3)
                                <x-heroicon-o-trophy class="w-4 h-4 text-yellow-500" />
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <x-heroicon-o-chart-bar class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                    <p class="text-gray-500">No course data available</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
