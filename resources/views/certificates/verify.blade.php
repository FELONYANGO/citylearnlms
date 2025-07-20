@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Certificate Verification</h1>
            <p class="text-gray-600 mt-2">Verify the authenticity of a certificate</p>
        </div>

        <!-- Verification Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('certificate.verify', '') }}" class="flex space-x-4">
                <input type="text" name="certificate_number" value="{{ $certificateNumber ?? '' }}"
                    placeholder="Enter certificate number (e.g., CERT-2024-001-0001)"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Verify
                </button>
            </form>
        </div>

        <!-- Verification Result -->
        @if(isset($valid))
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            @if($valid)
            <!-- Valid Certificate -->
            <div class="bg-green-50 border-b border-green-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-green-800">Certificate Verified</h3>
                        <p class="text-green-700">This certificate is authentic and valid.</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Certificate Number</h4>
                        <p class="text-lg font-semibold text-gray-900">{{ $certificateInfo['certificate_number'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Status</h4>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Valid
                        </span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Student Name</h4>
                        <p class="text-lg font-semibold text-gray-900">{{ $certificateInfo['student_name'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Course Title</h4>
                        <p class="text-lg font-semibold text-gray-900">{{ $certificateInfo['course_title'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Completion Date</h4>
                        <p class="text-lg font-semibold text-gray-900">{{ $certificateInfo['completion_date'] }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Issue Date</h4>
                        <p class="text-lg font-semibold text-gray-900">{{ $certificateInfo['issue_date'] }}</p>
                    </div>
                </div>

                <!-- Certificate Preview -->
                <div class="mt-8">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Certificate Preview</h4>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <div class="text-center">
                            <h5 class="text-xl font-bold text-gray-900 mb-2">Certificate of Completion</h5>
                            <p class="text-gray-600">This is to certify that</p>
                            <p class="text-2xl font-bold text-gray-900 my-2">{{ $certificateInfo['student_name'] }}</p>
                            <p class="text-gray-600">has successfully completed</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $certificateInfo['course_title'] }}</p>
                            <p class="text-sm text-gray-500 mt-4">Certificate Number: {{
                                $certificateInfo['certificate_number'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Invalid Certificate -->
            <div class="bg-red-50 border-b border-red-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-red-800">Certificate Not Found</h3>
                        <p class="text-red-700">{{ $message ?? 'The certificate number provided could not be verified.'
                            }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="text-center">
                    <h4 class="text-lg font-medium text-gray-900 mb-2">Certificate Number: {{ $certificateNumber }}</h4>
                    <p class="text-gray-600">This certificate number is not found in our system or may be invalid.</p>

                    <div class="mt-6">
                        <h5 class="text-sm font-medium text-gray-500 mb-2">Possible reasons:</h5>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• The certificate number was entered incorrectly</li>
                            <li>• The certificate has not been issued yet</li>
                            <li>• The certificate may have been revoked</li>
                            <li>• The certificate is from a different system</li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Information -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">About Certificate Verification</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">How to Verify</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Enter the certificate number exactly as shown</li>
                        <li>• Certificate numbers follow the format: CERT-YYYY-XXX-XXXX</li>
                        <li>• You can also scan the QR code on the certificate</li>
                        <li>• Verification is free and available 24/7</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Security Features</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Unique certificate numbers</li>
                        <li>• Digital verification system</li>
                        <li>• Tamper-proof design</li>
                        <li>• Real-time validation</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
