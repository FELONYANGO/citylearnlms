@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Course Summary -->
            <div class="border-b border-gray-200 bg-emerald-50 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $course->title }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Complete your enrollment</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-emerald-600">KSh {{ number_format($course->price, 2) }}</p>
                        <p class="text-sm text-gray-500">One-time payment</p>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <form action="{{ route('payments.process', $course) }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">

                <!-- Payment Method Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment Method</label>
                    <div class="space-y-4">
                        <label class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input type="radio" name="payment_method" value="mpesa" checked
                                    class="h-4 w-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                            </div>
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-700">M-Pesa</span>
                                <span class="block text-sm text-gray-500">Pay using M-Pesa mobile money</span>
                            </div>
                        </label>
                        <label class="relative flex items-start opacity-50 cursor-not-allowed">
                            <div class="flex items-center h-5">
                                <input type="radio" name="payment_method" value="card" disabled
                                    class="h-4 w-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                            </div>
                            <div class="ml-3">
                                <span class="block text-sm font-medium text-gray-700">Card Payment</span>
                                <span class="block text-sm text-gray-500">Coming soon</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- M-Pesa Phone Number -->
                <div class="mb-6" id="mpesa-input">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                        M-Pesa Phone Number
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">+</span>
                        </div>
                        <input type="tel" name="phone_number" id="phone_number"
                            class="focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                            placeholder="254712345678" value="{{ old('phone_number', auth()->user()->phone ?? '') }}">
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Enter the M-Pesa number that will be used for payment</p>
                    @error('phone_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Summary -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900">Order Summary</h3>
                    <div class="mt-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Course Price</span>
                            <span class="text-sm font-medium text-gray-900">KSh {{ number_format($course->price, 2)
                                }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <span class="text-base font-medium text-gray-900">Total</span>
                            <span class="text-base font-medium text-gray-900">KSh {{ number_format($course->price, 2)
                                }}</span>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="mt-6">
                    <div class="text-sm text-gray-500">
                        By proceeding with the payment, you agree to our
                        <a href="#" class="text-emerald-600 hover:text-emerald-500">Terms of Service</a> and
                        <a href="#" class="text-emerald-600 hover:text-emerald-500">Refund Policy</a>.
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Pay Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
