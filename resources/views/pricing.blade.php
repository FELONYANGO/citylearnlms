@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[500px] bg-gradient-to-br from-[#2B593F] via-[#1E4230] to-[#0F2419] overflow-hidden">
    <!-- Background overlay -->
    <div class="absolute inset-0 bg-black/20"></div>

    <!-- Animated background particles -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white opacity-5 rounded-full animate-pulse"></div>
        <div class="absolute top-3/4 right-1/4 w-32 h-32 bg-orange-300 opacity-10 rounded-full animate-bounce"></div>
        <div class="absolute top-1/2 left-3/4 w-48 h-48 bg-green-300 opacity-5 rounded-full animate-pulse delay-1000">
        </div>
    </div>

    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 min-h-[500px] flex items-center">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h1 class="text-5xl sm:text-6xl font-bold mb-6 animate-fade-in-up">
                Choose Your Plan
            </h1>
            <p class="text-xl text-gray-100 leading-relaxed animate-fade-in-up animation-delay-300">
                Select the perfect training package that fits your learning goals and budget.
                Unlock your potential with our comprehensive courses and programs.
            </p>
            <div class="mt-8 animate-fade-in-up animation-delay-500">
                <div class="inline-flex items-center space-x-4 bg-white/10 backdrop-blur-md rounded-full px-6 py-3">
                    <i class="fas fa-check text-green-400"></i>
                    <span class="text-sm">14-day money-back guarantee</span>
                    <div class="w-px h-4 bg-white/30"></div>
                    <i class="fas fa-star text-yellow-400"></i>
                    <span class="text-sm">Award-winning courses</span>
                    <div class="w-px h-4 bg-white/30"></div>
                    <i class="fas fa-users text-blue-400"></i>
                    <span class="text-sm">Join 5000+ learners</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Packages Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        @if($pricingPackages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
            @foreach($pricingPackages as $package)
            @php
            $colors = $package->getColorClasses();
            $isPopular = $package->is_popular;
            @endphp

            <div
                class="relative bg-white rounded-3xl shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-300 {{ $isPopular ? 'ring-4 ring-orange-400 ring-opacity-50' : '' }}">
                @if($isPopular)
                <div
                    class="absolute top-0 left-0 right-0 bg-gradient-to-r from-orange-400 to-red-400 text-white text-center py-3 font-semibold text-sm">
                    <i class="fas fa-star mr-1"></i>
                    Most Popular
                </div>
                @endif

                <div class="p-8 {{ $isPopular ? 'pt-16' : '' }}">
                    <!-- Package Header -->
                    <div class="text-center mb-8">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r {{ $colors['gradient'] }} rounded-full mb-4">
                            <i class="fas fa-graduation-cap text-white text-xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                        @if($package->description)
                        <p class="text-gray-600 text-sm">{{ $package->description }}</p>
                        @endif
                    </div>

                    <!-- Price -->
                    <div class="text-center mb-8">
                        <div class="flex items-baseline justify-center">
                            <span class="text-gray-600 text-sm">{{ $package->currency }}</span>
                            <span class="text-5xl font-bold {{ $colors['text'] }} mx-2">{{
                                number_format($package->price, 0) }}</span>
                            <span class="text-gray-600 text-sm">/ {{ $package->billing_period }}</span>
                        </div>
                        @if($package->billing_period === 'yearly')
                        <p class="text-sm text-gray-500 mt-2">
                            <span class="line-through">{{ $package->currency }} {{ number_format($package->price * 12,
                                0) }}</span>
                            <span class="{{ $colors['text'] }} font-semibold ml-2">Save 17%</span>
                        </p>
                        @endif
                    </div>

                    <!-- Features -->
                    <div class="mb-8">
                        <ul class="space-y-4">
                            @foreach($package->features as $feature)
                            <li class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-5 h-5 {{ $colors['bg'] }} rounded-full flex items-center justify-center mt-0.5">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <span class="text-gray-700 text-sm">{{ $feature['feature'] }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- CTA Button -->
                    <div class="text-center">
                        <a href="{{ $package->button_link ?: '#' }}"
                            class="block w-full bg-gradient-to-r {{ $colors['gradient'] }} {{ $colors['hover'] }} text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                            {{ $package->button_text }}
                        </a>
                    </div>
                </div>

                <!-- Decorative Elements -->
                <div class="absolute -top-4 -right-4 w-24 h-24 {{ $colors['bg'] }} opacity-10 rounded-full"></div>
                <div class="absolute -bottom-4 -left-4 w-20 h-20 {{ $colors['bg'] }} opacity-5 rounded-full"></div>
            </div>
            @endforeach
        </div>
        @else
        <!-- No packages available -->
        <div class="text-center py-20">
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-dollar-sign text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No Pricing Packages Available</h3>
                    <p class="text-gray-600 mb-6">
                        Our pricing packages are currently being updated. Please check back soon or contact us for more
                        information.
                    </p>
                    <a href="{{ route('contact') }}"
                        class="inline-block bg-gradient-to-r from-[#2B593F] to-[#1E4230] text-white font-semibold py-3 px-6 rounded-xl hover:from-[#1E4230] hover:to-[#0F2419] transition-all duration-300 transform hover:scale-105">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600">Get answers to common questions about our pricing and packages</p>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-question-circle text-[#2B593F] mr-3"></i>
                        Can I switch between plans?
                    </h3>
                    <p class="text-gray-600 pl-9">
                        Yes! You can upgrade or downgrade your plan at any time. Changes will take effect at the next
                        billing cycle.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-question-circle text-[#2B593F] mr-3"></i>
                        Is there a money-back guarantee?
                    </h3>
                    <p class="text-gray-600 pl-9">
                        Absolutely! We offer a 14-day money-back guarantee. If you're not satisfied, contact us for a
                        full refund.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-question-circle text-[#2B593F] mr-3"></i>
                        What payment methods do you accept?
                    </h3>
                    <p class="text-gray-600 pl-9">
                        We accept M-Pesa, Visa, Mastercard, and bank transfers. All payments are processed securely.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-question-circle text-[#2B593F] mr-3"></i>
                        Do you offer discounts for organizations?
                    </h3>
                    <p class="text-gray-600 pl-9">
                        Yes! We offer special rates for organizations and bulk enrollments. Contact our sales team for a
                        custom quote.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-[#2B593F] via-[#1E4230] to-[#0F2419]">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h2 class="text-4xl font-bold mb-6">Ready to Get Started?</h2>
            <p class="text-xl text-gray-100 mb-8">
                Join thousands of learners who have already transformed their careers with our training programs.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('contact') }}"
                    class="bg-white text-[#2B593F] font-semibold py-4 px-8 rounded-xl hover:bg-gray-100 transition-colors transform hover:scale-105">
                    <i class="fas fa-phone mr-2"></i>
                    Talk to Sales
                </a>
                <a href="{{ route('register') }}"
                    class="bg-orange-400 text-white font-semibold py-4 px-8 rounded-xl hover:bg-orange-500 transition-colors transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>
                    Start Free Trial
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out;
    }

    .animation-delay-300 {
        animation-delay: 0.3s;
    }

    .animation-delay-500 {
        animation-delay: 0.5s;
    }
</style>
@endsection