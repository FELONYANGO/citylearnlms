@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Your Certificate</h1>
            <p class="text-gray-600 mt-2">Congratulations on completing your course!</p>
        </div>

        <!-- Certificate Display -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Certificate of Completion</h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('certificates.download', $course) }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Download PDF
                        </a>
                        <button onclick="window.print()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Certificate Content -->
            <div class="p-6">
                @include('certificates.templates.classic', ['certificateData' => $certificateData])
            </div>

            <!-- Certificate Details -->
            <div class="bg-gray-50 px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Certificate Number</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $certificateData['certificate_number'] }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Issue Date</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $certificateData['issue_date'] }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Verification</h3>
                        <a href="{{ route('certificate.verify', $certificateData['certificate_number']) }}"
                            target="_blank" class="text-blue-600 hover:text-blue-800 font-medium">
                            Verify Certificate
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Certificate Features</h3>
                <ul class="space-y-2 text-gray-600">
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Unique certificate number
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Digital verification
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Professional design
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Print-ready format
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Next Steps</h3>
                <div class="space-y-3">
                    <a href="{{ route('courses.learn', $course) }}"
                        class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        Continue Learning
                    </a>
                    <a href="{{ route('dashboard') }}"
                        class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {

        .bg-gray-100,
        .bg-white,
        .bg-gray-50 {
            background: white !important;
        }

        .shadow,
        .shadow-lg {
            box-shadow: none !important;
        }

        .rounded-lg {
            border-radius: 0 !important;
        }

        .p-6,
        .px-6,
        .py-4,
        .py-8 {
            padding: 0 !important;
        }

        .mb-8,
        .mt-8 {
            margin: 0 !important;
        }

        .max-w-7xl {
            max-width: none !important;
        }

        .mx-auto {
            margin: 0 !important;
        }
    }
</style>
@endsection
