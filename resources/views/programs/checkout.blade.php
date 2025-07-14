@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-6">Checkout - {{ $program->title }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Program Summary -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Program Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Program Fee:</span>
                            <span>KSh {{ number_format($program->total_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-semibold border-t pt-2">
                            <span>Total Amount:</span>
                            <span>KSh {{ number_format($program->total_fee, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Payment Details</h3>
                    <form action="{{ route('programs.payment.process', $program) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">M-Pesa Phone Number</label>
                            <div class="mt-1">
                                <input type="text" name="phone" id="phone"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="254700000000"
                                    value="{{ old('phone') }}"
                                    required>
                            </div>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox"
                                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                    required>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="font-medium text-gray-700">
                                    I agree to the terms and conditions
                                </label>
                            </div>
                        </div>
                        @error('terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-6">
                            <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Pay with M-Pesa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
