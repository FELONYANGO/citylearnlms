<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-gray-500">Total Enrollments</div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</div>
        </div>

        <div class="bg-green-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-green-600">Active Enrollments</div>
            <div class="text-2xl font-bold text-green-700">{{ number_format($stats['active']) }}</div>
        </div>

        <div class="bg-blue-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-blue-600">Completed</div>
            <div class="text-2xl font-bold text-blue-700">{{ number_format($stats['completed']) }}</div>
        </div>

        <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-yellow-600">Pending</div>
            <div class="text-2xl font-bold text-yellow-700">{{ number_format($stats['pending']) }}</div>
        </div>

        <div class="bg-purple-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-purple-600">With Certificates</div>
            <div class="text-2xl font-bold text-purple-700">{{ number_format($stats['with_certificates']) }}</div>
        </div>

        <div class="bg-indigo-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-indigo-600">Total Revenue</div>
            <div class="text-2xl font-bold text-indigo-700">${{ number_format($stats['total_revenue'], 2) }}</div>
        </div>
    </div>

    <div class="text-sm text-gray-500 text-center">
        Statistics as of {{ now()->format('M d, Y H:i') }}
    </div>
</div>
