@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="text-center">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-blue-600 animate-spin" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold mb-2">Payment Processing</h2>
                        <p class="text-gray-600">Please check your phone to complete the payment</p>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-2">Order Summary</h3>
                        <div class="border rounded p-4 bg-gray-50">
                            <div class="flex justify-between mb-2">
                                <span>Order #{{ $order->id }}</span>
                                <span class="font-semibold">{{ $order->currency }} {{
                                    number_format($order->total_amount, 2) }}</span>
                            </div>
                            @foreach($order->items as $item)
                            <div class="text-sm text-gray-600">
                                {{ $item->orderable->title }}
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-blue-800">
                                    An M-Pesa payment request has been sent to your phone. Please check your phone and
                                    enter your PIN to complete the payment.
                                </span>
                            </div>
                        </div>

                        <div class="text-sm text-gray-500">
                            <p>• Check your phone for the M-Pesa payment request</p>
                            <p>• Enter your M-Pesa PIN to authorize the payment</p>
                            <p>• You will be redirected automatically once payment is complete</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t">
                        <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-800">
                            ← Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-refresh the page every 10 seconds to check payment status
        setTimeout(function() {
            window.location.reload();
        }, 10000);
</script>
@endsection
