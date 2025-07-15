@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Payment Processing Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#2B593F] to-[#1E4230] p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-bold text-white">Payment Processing</h2>
                            <p class="text-green-100">Please complete the payment on your phone</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-white">{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</p>
                        <p class="text-sm text-green-200">Order #{{ $order->id }}</p>
                    </div>
                </div>
                    </div>

            <div class="p-8">
                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#2B593F] to-orange-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-{{ $item->orderable_type === 'App\\Models\\Course' ? 'book' : 'graduation-cap' }} text-white"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">{{ $item->orderable->title }}</p>
                                <p class="text-sm text-gray-500">{{ class_basename($item->orderable_type) }}</p>
                            </div>
                        </div>
                        <span class="font-semibold text-gray-900">{{ number_format($item->price, 2) }}</span>
                    </div>
                    @endforeach
                    </div>

                <!-- Payment Instructions -->
                <div class="space-y-6">
                    <!-- Status Message -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-blue-900">Payment Request Sent</h4>
                                <p class="mt-2 text-blue-700">An M-Pesa payment request has been sent to your phone. Please check your phone and follow the instructions to complete the payment.</p>
                            </div>
                            </div>
                        </div>

                    <!-- Steps -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-green-600 font-bold">1</span>
                            </div>
                            <h5 class="font-semibold text-gray-900 mb-2">Check Your Phone</h5>
                            <p class="text-sm text-gray-600">Look for the M-Pesa payment prompt on your phone</p>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-orange-600 font-bold">2</span>
                            </div>
                            <h5 class="font-semibold text-gray-900 mb-2">Enter PIN</h5>
                            <p class="text-sm text-gray-600">Enter your M-Pesa PIN to authorize payment</p>
                    </div>

                        <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-blue-600 font-bold">3</span>
                            </div>
                            <h5 class="font-semibold text-gray-900 mb-2">Auto-Redirect</h5>
                            <p class="text-sm text-gray-600">You'll be redirected once payment is complete</p>
                        </div>
                    </div>
                </div>

                <!-- Back Link -->
                <div class="mt-8 pt-6 border-t text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-gray-600 hover:text-[#2B593F] transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@php
$payment = $order->payment()->latest()->first();
@endphp

<script>
// Check payment status every 5 seconds
const checkPayment = async () => {
    try {
        const response = await fetch(`/api/payments/status/${@json($payment->id)}`);
        const data = await response.json();

        if (data.status === 'completed') {
            // Payment successful - redirect to success page
            window.location.href = `/payments/success/${@json($order->id)}`;
        } else if (data.status === 'failed') {
            // Payment failed - redirect to failed page
            window.location.href = `/payments/failed/${@json($order->id)}`;
        } else {
            // Still pending - check again in 5 seconds
            setTimeout(checkPayment, 5000);
        }
    } catch (error) {
        console.error('Error checking payment status:', error);
        // Retry in 5 seconds even if there was an error
        setTimeout(checkPayment, 5000);
    }
};

// Start checking payment status
checkPayment();
</script>
@endsection
