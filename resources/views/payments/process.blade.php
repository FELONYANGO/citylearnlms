@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Payment Processing</h2>

                <div class="mb-8">
                    <h3 class="text-lg font-medium mb-2">Order Summary</h3>
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

                <form action="{{ route('payments.initiate', $order) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">M-Pesa Phone Number</label>
                        <div class="mt-1">
                            <input type="text" name="phone" id="phone"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                placeholder="254712345678" value="{{ old('phone') }}" required>
                        </div>
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="terms" id="terms"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">
                                I agree to the terms and conditions
                            </label>
                            <p class="text-gray-500">
                                By proceeding, you agree to our terms of service and privacy policy.
                            </p>
                        </div>
                    </div>
                    @error('terms')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Pay with M-Pesa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection