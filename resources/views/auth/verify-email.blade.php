@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center" style="background: linear-gradient(135deg, #2B593F 0%, #1e3d2b 100%);">
    <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8 m-4">
        <div class="text-center mb-8">
            <div class="mx-auto h-16 w-16 flex items-center justify-center rounded-full mb-4" style="background-color: #2B593F;">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                Verify Your Email Address
            </h2>
            <p class="text-gray-600">
                We've sent a verification link to your email
            </p>
        </div>

        <div class="space-y-6">
            <div class="p-4 rounded-lg border border-gray-200 bg-gray-50">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="h-5 w-5 rounded-full flex items-center justify-center" style="background-color: #2B593F;">
                            <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">
                            Check your email
                        </h3>
                        <div class="mt-1 text-sm text-gray-600">
                            <p>
                                We've sent a verification link to <strong class="text-gray-900">{{ auth()->user()->email }}</strong>.
                                Click the link in the email to verify your account and start learning!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="p-4 rounded-lg border" style="background-color: #f0f9f4; border-color: #2B593F;">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="h-5 w-5 rounded-full flex items-center justify-center" style="background-color: #2B593F;">
                                <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium" style="color: #2B593F;">
                                Verification email sent!
                            </h3>
                            <div class="mt-1 text-sm text-gray-600">
                                <p>A new verification link has been sent to your email address.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('status') == 'registration-success')
                <div class="p-4 rounded-lg border" style="background-color: #f0f9f4; border-color: #2B593F;">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="h-5 w-5 rounded-full flex items-center justify-center" style="background-color: #2B593F;">
                                <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium" style="color: #2B593F;">
                                Registration successful!
                            </h3>
                            <div class="mt-1 text-sm text-gray-600">
                                <p>Your account has been created successfully. Please verify your email to continue.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-4">
                        Didn't receive the email? Check your spam folder or request a new one.
                    </p>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition duration-150 ease-in-out hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: #2B593F; focus:ring-color: #2B593F;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Resend Verification Email
                        </button>
                    </form>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-500">
                        Need help? Contact our support team at
                        <a href="mailto:support@nairobicounty.go.ke" class="hover:underline" style="color: #2B593F;">
                            support@nairobicounty.go.ke
                        </a>
                    </p>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <form method="POST" action="{{ route('logout') }}" class="text-center">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline">
                            Log out and use a different account
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <div class="flex items-center justify-center space-x-2 text-sm text-gray-500">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <span>Your information is secure and protected</span>
            </div>
        </div>
    </div>
</div>
@endsection
