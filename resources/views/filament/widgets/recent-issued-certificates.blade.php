<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Recent Issued Certificates</h3>
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Certificate</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Issued At</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($recentIssued as $issued)
            <tr>
                <td class="px-4 py-2 whitespace-nowrap">{{ $issued->user->name ?? '-' }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $issued->certificate->name ?? '-' }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $issued->issued_at ? $issued->issued_at->format('Y-m-d H:i')
                    : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-4 py-2 text-center text-gray-400">No certificates issued yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
