<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="mb-8">
                        <svg class="mx-auto h-16 w-16 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <h2 class="mt-4 text-2xl font-semibold text-gray-900">Payment Successful!</h2>
                        <p class="mt-2 text-gray-600">
                            Thank you for your payment. Your {{ $type }} enrollment is now complete.
                        </p>
                    </div>

                    <div class="mb-8 text-left">
                        <h3 class="text-lg font-medium mb-2">Order Details</h3>
                        <div class="border rounded p-4">
                            <div class="flex justify-between mb-4">
                                <span>Order #{{ $order->id }}</span>
                                <span>{{ number_format($order->total_amount, 2) }} {{ $order->currency }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <div class="mb-2">
                                    <span class="font-medium">{{ ucfirst($type) }}:</span>
                                    <span>{{ $purchasable->title }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Transaction ID:</span>
                                    <span>{{ $order->payments->last()->transaction_id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        @if($type === 'course')
                        <a href="{{ route('courses.learn', $purchasable) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Start Learning
                        </a>
                        @else
                        <a href="{{ route('programs.show', $purchasable) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            View Program
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>