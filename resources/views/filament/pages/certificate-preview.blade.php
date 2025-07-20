<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow p-6">
            {{ $this->form }}
        </div>

        <!-- Certificate Preview Section -->
        @if($this->selectedEnrollment && isset($certificateData))
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Certificate Preview</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Previewing certificate for: <strong>{{ $enrollment->user->name }}</strong> - <strong>{{ $enrollment->course->title }}</strong>
                </p>
            </div>

            <div class="p-6">
                <!-- Template Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Template Style</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="template" value="classic"
                                   {{ $template === 'classic' ? 'checked' : '' }}
                                   class="mr-2" wire:model="selectedTemplate">
                            <span class="text-sm">Classic</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="template" value="modern"
                                   {{ $template === 'modern' ? 'checked' : '' }}
                                   class="mr-2" wire:model="selectedTemplate">
                            <span class="text-sm">Modern</span>
                        </label>
                    </div>
                </div>

                <!-- Certificate Display -->
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Certificate Preview</span>
                            <div class="flex space-x-2">
                                <button onclick="window.print()"
                                        class="text-xs px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Print Preview
                                </button>
                                <button onclick="window.open('{{ route('certificates.download', $enrollment->course) }}', '_blank')"
                                        class="text-xs px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                    Download PDF
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-white">
                        @if($template === 'classic')
                            @include('certificates.templates.classic', ['certificateData' => $certificateData])
                        @else
                            @include('certificates.templates.modern', ['certificateData' => $certificateData])
                        @endif
                    </div>
                </div>

                <!-- Certificate Details -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Certificate Number</h4>
                        <p class="text-lg font-semibold text-gray-900">{{ $certificateData['certificate_number'] ?? 'Not issued' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Issue Date</h4>
                        <p class="text-lg font-semibold text-gray-900">{{ $certificateData['issue_date'] ?? 'Not issued' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500">Status</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $enrollment->certificate_issued ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $enrollment->certificate_issued ? 'Issued' : 'Not Issued' }}
                        </span>
                    </div>
                </div>

                <!-- Verification Link -->
                @if($enrollment->certificate_issued)
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Verification Link</h4>
                    <div class="flex items-center space-x-2">
                        <input type="text"
                               value="{{ route('certificate.verify', $certificateData['certificate_number']) }}"
                               readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-white text-sm">
                        <button onclick="navigator.clipboard.writeText('{{ route('certificate.verify', $certificateData['certificate_number']) }}')"
                                class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            Copy
                        </button>
                        <a href="{{ route('certificate.verify', $certificateData['certificate_number']) }}"
                           target="_blank"
                           class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                            Verify
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @else
        <!-- No Selection State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Certificate Selected</h3>
            <p class="text-gray-600">Please select an enrollment from the dropdown above to preview the certificate.</p>
        </div>
        @endif
    </div>

    <style>
        @media print {
            .bg-white, .bg-gray-50, .bg-blue-50 {
                background: white !important;
            }

            .shadow, .border {
                box-shadow: none !important;
                border: none !important;
            }

            .p-6, .p-4, .px-4, .py-2 {
                padding: 0 !important;
            }

            .space-y-6 > * + * {
                margin-top: 0 !important;
            }

            .rounded-lg {
                border-radius: 0 !important;
            }
        }
    </style>
</x-filament-panels::page>
