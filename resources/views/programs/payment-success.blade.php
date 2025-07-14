@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="text-center">
                <div class="rounded-full h-16 w-16 bg-green-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold mb-2">Payment Successful!</h2>
                <p class="text-gray-600 mb-6">You have been successfully enrolled in {{ $program->title }}</p>

                <div class="max-w-md mx-auto bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="font-semibold mb-4">Payment Details:</h3>
                    <div class="space-y-2 text-left">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount Paid:</span>
                            <span class="font-medium">KSh {{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Method:</span>
                            <span class="font-medium">M-Pesa</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Transaction ID:</span>
                            <span class="font-medium">{{ $payment->id }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-x-4">
                    <a href="{{ route('programs.show', $program) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        View Program
                    </a>
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
