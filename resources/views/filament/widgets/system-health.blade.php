<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-heart class="w-5 h-5 text-red-500" />
                System Health Overview
            </div>
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Health Indicators -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Health Indicators</h3>
                <div class="space-y-4">
                    @foreach($healthIndicators as $indicator)
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                                        @if($indicator['status'] === 'healthy') bg-green-100 text-green-600
                                        @elseif($indicator['status'] === 'warning') bg-yellow-100 text-yellow-600
                                        @else bg-red-100 text-red-600
                                        @endif
                                    ">
                                        <x-dynamic-component :component="$indicator['icon']" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $indicator['name'] }}</h4>
                                        <p class="text-sm text-gray-500">{{ $indicator['description'] }}</p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-2xl font-bold
                                        @if($indicator['status'] === 'healthy') text-green-600
                                        @elseif($indicator['status'] === 'warning') text-yellow-600
                                        @else text-red-600
                                        @endif
                                    ">
                                        {{ $indicator['value'] }}%
                                    </div>
                                    <div class="text-xs font-medium
                                        @if($indicator['status'] === 'healthy') text-green-600
                                        @elseif($indicator['status'] === 'warning') text-yellow-600
                                        @else text-red-600
                                        @endif
                                    ">
                                        {{ ucfirst($indicator['status']) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-3">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all duration-300
                                        @if($indicator['status'] === 'healthy') bg-green-500
                                        @elseif($indicator['status'] === 'warning') bg-yellow-500
                                        @else bg-red-500
                                        @endif
                                    " style="width: {{ $indicator['value'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-4">
                    @foreach($recentActivity as $activity)
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <x-dynamic-component :component="$activity['icon']" class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $activity['label'] }}</h4>
                                        <p class="text-sm text-gray-500">Latest activity metrics</p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ $activity['value'] }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        items
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Overall System Status -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 mt-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                <x-heroicon-o-shield-check class="w-5 h-5" />
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">System Status</h4>
                                <p class="text-sm text-blue-600 font-medium">
                                    @php
                                        $healthyCount = collect($healthIndicators)->where('status', 'healthy')->count();
                                        $totalCount = count($healthIndicators);
                                        $overallHealth = $healthyCount / $totalCount;
                                    @endphp

                                    @if($overallHealth >= 0.75)
                                        🟢 All systems operational
                                    @elseif($overallHealth >= 0.5)
                                        🟡 Some systems need attention
                                    @else
                                        🔴 Multiple systems require immediate attention
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
