@extends('layouts.app')

@section('content')
<!-- Modern Checkout Hero Section -->
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
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 animate-fade-in">
                    <span class="bg-gradient-to-r from-[#2B593F] to-orange-400 bg-clip-text text-transparent">
                        Secure Checkout
                    </span>
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    You're just one step away from starting your learning journey
                </p>
                <div class="w-24 h-1 bg-gradient-to-r from-[#2B593F] to-orange-400 mx-auto rounded-full"></div>
            </div>

            <!-- Centered Payment Card -->
            <div class="flex justify-center">
                <div class="w-full max-w-2xl">
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-[#2B593F] to-[#1E4230] p-6 text-white">
                            <h3 class="text-2xl font-bold mb-2">Order Summary</h3>
                            <p class="text-green-100">{{ $purchasable->title }}</p>
                        </div>

                        <!-- Order Details -->
                        <div class="p-8 space-y-6">
                            <!-- Item Details -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900">{{ $type === 'course' ? 'Course' :
                                            'Program' }} Fee</h4>
                                        <p class="text-sm text-gray-500">{{ $purchasable->title }}</p>
                                        @if($type === 'program' && $purchasable->courses &&
                                        $purchasable->courses->count() > 0)
                                        <p class="text-xs text-gray-400 mt-1">{{ $purchasable->courses->count() }}
                                            courses included</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xl font-bold text-gray-900">
                                            {{ $type === 'course' ? $purchasable->formatted_price : 'KSh ' .
                                            number_format($purchasable->total_fee, 2) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Course/Program Features -->
                                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                                    <h5 class="font-semibold text-gray-900 mb-3">What's included:</h5>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div class="flex items-center text-gray-700">
                                            <div
                                                class="w-5 h-5 bg-[#2B593F] rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <span class="text-sm">{{ $type === 'course' ? 'Lifetime Access' : 'Multiple
                                                Course Access' }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <div
                                                class="w-5 h-5 bg-[#2B593F] rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <span class="text-sm">Certificate of Completion</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <div
                                                class="w-5 h-5 bg-[#2B593F] rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <span class="text-sm">Expert Support</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <div
                                                class="w-5 h-5 bg-[#2B593F] rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <span class="text-sm">Mobile Access</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Divider -->
                                <div class="border-t border-gray-200"></div>

                                <!-- Total -->
                                <div class="flex justify-between items-center pt-2">
                                    <div>
                                        <h4 class="text-xl font-bold text-gray-900">Total</h4>
                                        <p class="text-sm text-gray-500">One-time payment</p>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-3xl font-bold bg-gradient-to-r from-[#2B593F] to-orange-400 bg-clip-text text-transparent">
                                            {{ $type === 'course' ? $purchasable->formatted_price : 'KSh ' .
                                            number_format($purchasable->total_fee, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Form -->
                            <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="purchasable_type" value="{{ $type }}">
                                <input type="hidden" name="purchasable_id" value="{{ $purchasable->id }}">

                                <!-- Secure Payment Button -->
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
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        Proceed to Secure Payment
                                    </span>

                                    <!-- Shimmer Effect -->
                                    <div
                                        class="absolute inset-0 -skew-x-12 bg-gradient-to-r from-transparent via-white/20 to-transparent opacity-0 group-hover:opacity-100 group-hover:animate-pulse transition-opacity duration-1000">
                                    </div>
                                </button>

                                <!-- Security Information -->
                                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-lock text-green-500 mr-2"></i>
                                            <span>Secure & Encrypted</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-mobile-alt text-orange-400 mr-2"></i>
                                            <span>M-Pesa Payment</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-undo text-blue-500 mr-2"></i>
                                            <span>Money Back Guarantee</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-headset text-purple-500 mr-2"></i>
                                            <span>24/7 Support</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Terms -->
                                <div class="text-center text-xs text-gray-500 space-y-2">
                                    <p>By proceeding, you agree to our
                                        <a href="#" class="text-[#2B593F] hover:text-orange-400 font-medium">Terms of
                                            Service</a>
                                        and
                                        <a href="#" class="text-[#2B593F] hover:text-orange-400 font-medium">Privacy
                                            Policy</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Trust Section -->
            <div class="mt-16 text-center">
                <div class="flex flex-wrap justify-center items-center gap-4 sm:gap-8 text-gray-500">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-shield-alt text-green-500 text-xl"></i>
                        <span class="font-medium text-sm sm:text-base">SSL Secured</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fab fa-cc-visa text-blue-600 text-xl"></i>
                        <span class="font-medium text-sm sm:text-base">M-Pesa</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-award text-orange-400 text-xl"></i>
                        <span class="font-medium text-sm sm:text-base">Certified Training</span>
                    </div>
                </div>
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