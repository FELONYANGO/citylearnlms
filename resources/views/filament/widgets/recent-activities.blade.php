<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-clock class="w-5 h-5 text-gray-500" />
                Recent Activities
            </div>
        </x-slot>

        <div class="space-y-4">
            @forelse($activities as $activity)
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            @if($activity['color'] === 'success') bg-green-100 text-green-600
                            @elseif($activity['color'] === 'danger') bg-red-100 text-red-600
                            @elseif($activity['color'] === 'warning') bg-yellow-100 text-yellow-600
                            @elseif($activity['color'] === 'info') bg-blue-100 text-blue-600
                            @else bg-gray-100 text-gray-600
                            @endif
                        ">
                            <x-dynamic-component :component="$activity['icon']" class="w-5 h-5" />
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $activity['title'] }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $activity['time'] }}
                        </p>
                    </div>

                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if($activity['color'] === 'success') bg-green-100 text-green-800
                            @elseif($activity['color'] === 'danger') bg-red-100 text-red-800
                            @elseif($activity['color'] === 'warning') bg-yellow-100 text-yellow-800
                            @elseif($activity['color'] === 'info') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif
                        ">
                            @if($activity['type'] === 'enrollment')
                                Enrolled
                            @elseif($activity['type'] === 'quiz_attempt')
                                Quiz
                            @elseif($activity['type'] === 'user_registration')
                                New User
                            @elseif($activity['type'] === 'course_completion')
                                Completed
                            @endif
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <x-heroicon-o-inbox class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                    <p class="text-gray-500">No recent activities found</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
