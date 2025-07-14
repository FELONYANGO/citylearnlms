<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center">
                    <div class="mb-8">
                        <svg class="mx-auto h-16 w-16 text-red-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <h2 class="mt-4 text-2xl font-semibold text-gray-900">Payment Cancelled</h2>
                        <p class="mt-2 text-gray-600">
                            Your payment was cancelled. No charges have been made.
                        </p>
                    </div>

                    <div class="mb-8 text-left">
                        <h3 class="text-lg font-medium mb-2">Order Details</h3>
                        <div class="border rounded p-4">
                            <div class="flex justify-between mb-4">
                                <span>Order #{{ $order->id }}</span>
                                <span>{{ number_format($order->total_amount, 2) }} {{ $order->currency }}</span>
                            </div>
                            @foreach($order->items as $item)
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>{{ class_basename($item->orderable_type) }}</span>
                                <span>{{ $item->orderable->title }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-8 space-x-4">
                        <a href="{{ route('payments.process', $order) }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Try Again
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>