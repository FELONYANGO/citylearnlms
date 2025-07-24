<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Certificate Selection Form -->
        <div class="bg-white rounded-lg shadow p-6">
            {{ $this->form }}
        </div>

        @if($certificate)
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Certificate Preview</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Previewing certificate: <strong>{{ $certificate->name }}</strong> (Course: <strong>{{
                        $certificate->course->title }}</strong>)
                </p>
            </div>

            <div class="p-6">
                <!-- Render the template HTML, replacing placeholders with metadata -->
                <div class="border border-gray-200 rounded-lg overflow-hidden p-4 bg-white">
                    {!! strtr(
                    $certificate->template->html_content,
                    collect($certificate->metadata ?? [])->mapWithKeys(fn($v, $k) => ['{{'.$k.'}}' => $v])->all()
                    ) !!}
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Certificate Selected</h3>
            <p class="text-gray-600">Please select a certificate from the dropdown above to preview.</p>
        </div>
        @endif
    </div>
</x-filament-panels::page>
