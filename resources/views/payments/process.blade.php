@extends('layouts.app')

@section('content')
<!-- Modern Payment Processing Section -->
<section class="relative min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-[#2B593F] opacity-5 rounded-full animate-pulse"></div>
        <div class="absolute top-3/4 right-1/4 w-32 h-32 bg-orange-300 opacity-10 rounded-full animate-bounce"></div>
        <div class="absolute top-1/2 left-3/4 w-48 h-48 bg-green-300 opacity-5 rounded-full animate-pulse delay-1000">
        </div>
    </div>

    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-16 space-y-4">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-[#2B593F] to-[#1E4230] rounded-full mb-6 shadow-xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 animate-fade-in">
                    <span class="bg-gradient-to-r from-[#2B593F] to-orange-400 bg-clip-text text-transparent">
                        Payment Processing
                    </span>
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Complete your payment securely with M-Pesa
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-[#2B593F] to-orange-400 mx-auto rounded-full"></div>
            </div>

            <!-- Centered Payment Card -->
            <div class="flex justify-center">
                <div class="w-full max-w-2xl">
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                        <!-- Header with Order Info -->
                        <div class="bg-gradient-to-r from-[#2B593F] to-[#1E4230] p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-2xl font-bold mb-2">Order #{{ $order->id }}</h3>
                                    <p class="text-green-100">Ready for payment</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-bold">{{ number_format($order->total_amount, 2) }}</p>
                                    <p class="text-green-200 text-sm">{{ $order->currency }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="p-8 space-y-6">
                            <!-- Order Items -->
                            <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                                <h4 class="font-semibold text-gray-900 mb-4">Order Summary</h4>
                                @foreach($order->items as $item)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-[#2B593F] to-orange-400 rounded-full flex items-center justify-center mr-4">
                                            <i
                                                class="fas fa-{{ class_basename($item->orderable_type) === 'Course' ? 'book' : 'graduation-cap' }} text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $item->orderable->title }}</p>
                                            <p class="text-sm text-gray-500">{{ class_basename($item->orderable_type) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">{{ number_format($item->price, 2) }} {{
                                            $item->currency }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Payment Form -->
                            <form action="{{ route('payments.initiate', $order) }}" method="POST" class="space-y-6">w
                                @csrf

                                <!-- M-Pesa Phone Number -->
                                <div class="space-y-2">
                                    <label for="phone" class="block text-sm font-semibold text-gray-900">
                                        M-Pesa Phone Number
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" name="phone" id="phone"
                                            class="block w-full pl-12 pr-4 py-4 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F] transition-all duration-200 text-lg"
                                            placeholder="254712345678"
                                            value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                    </div>
                                    <p class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                                        Enter the M-Pesa number that will receive the payment request
                                    </p>
                                    @error('phone')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <!-- Payment Method Info -->
                                <div
                                    class="bg-gradient-to-r from-green-50 to-orange-50 rounded-xl p-4 border border-green-200">
                                    <div class="flex items-center">
                                        <div
                                            class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                            <i class="fab fa-cc-mastercard text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-semibold text-gray-900">M-Pesa Payment</h5>
                                            <p class="text-sm text-gray-600">Safe, secure, and instant mobile payment
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms and Conditions -->
                                <div class="flex items-start space-x-3">
                                    <div class="flex items-center h-6">
                                        <input type="checkbox" name="terms" id="terms"
                                            class="h-5 w-5 rounded border-gray-300 text-[#2B593F] focus:ring-[#2B593F] transition-colors duration-200"
                                            required>
                                    </div>
                                    <div class="text-sm">
                                        <label for="terms" class="font-medium text-gray-700 cursor-pointer">
                                            I agree to the terms and conditions
                                        </label>
                                        <p class="text-gray-500 mt-1">
                                            By proceeding, you agree to our
                                            <a href="#" class="text-[#2B593F] hover:text-orange-400 font-medium">Terms
                                                of Service</a>
                                            and
                                            <a href="#" class="text-[#2B593F] hover:text-orange-400 font-medium">Privacy
                                                Policy</a>
                                        </p>
                                    </div>
                                </div>
                                @error('terms')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                                @enderror

                                <!-- Payment Button -->
                                <button type="submit"
                                    class="group relative w-full bg-gradient-to-r from-[#2B593F] via-[#1E4230] to-[#0F2419] hover:from-[#1E4230] hover:via-[#0F2419] hover:to-[#2B593F] text-white py-4 px-6 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl overflow-hidden">
                                    <!-- Button Background Animation -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-orange-400 to-red-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>

                                    <!-- Button Content -->
                                    <span class="relative z-10 flex items-center justify-center">
                                        <svg class="w-6 h-6 mr-3 group-hover:animate-pulse" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        Pay with M-Pesa
                                    </span>

                                    <!-- Shimmer Effect -->
                                    <div
                                        class="absolute inset-0 -skew-x-12 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 group-hover:opacity-100 group-hover:animate-pulse transition-opacity duration-1000">
                                    </div>
                                </button>

                                <!-- Security Information -->
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <h5 class="font-semibold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                        Payment Security
                                    </h5>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-600">
                                        <div class="flex items-center">
                                            <i class="fas fa-lock text-green-500 mr-2"></i>
                                            <span>256-bit SSL Encryption</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-mobile-alt text-orange-400 mr-2"></i>
                                            <span>Instant Mobile Payment</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-undo text-blue-500 mr-2"></i>
                                            <span>Money Back Guarantee</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-headset text-purple-500 mr-2"></i>
                                            <span>24/7 Customer Support</span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How It Works Section -->
            <div class="mt-16 bg-white rounded-2xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-center text-gray-900 mb-8">How M-Pesa Payment Works</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-[#2B593F] to-green-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white font-bold text-xl">1</span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Enter Phone Number</h4>
                        <p class="text-gray-600 text-sm">Enter your M-Pesa registered phone number</p>
                    </div>

                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white font-bold text-xl">2</span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Receive STK Push</h4>
                        <p class="text-gray-600 text-sm">Check your phone for payment request</p>
                    </div>

                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-white font-bold text-xl">3</span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Enter PIN & Confirm</h4>
                        <p class="text-gray-600 text-sm">Complete payment and start learning</p>
                    </div>
                </div>
            </div>

            <!-- Back to Home Link -->
            <div class="mt-8 text-center">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-gray-600 hover:text-[#2B593F] transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Custom animations -->
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }
</style>
@endsection
