@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<!-- Hero Section with Video Background -->
<section class="relative min-h-[700px] bg-gradient-to-br from-[#2B593F] via-[#1E4230] to-[#0F2419] overflow-hidden">
    <!-- Background Video/Image -->
    @if($homepageSettings->hero_video)
    <video class="absolute inset-0 w-full h-full object-cover opacity-30" autoplay muted loop playsinline>
        <source src="{{ Storage::url($homepageSettings->hero_video) }}" type="video/mp4">
    </video>
    @elseif($homepageSettings->hero_image)
    <div class="absolute inset-0 w-full h-full bg-cover bg-center opacity-30"
        style="background-image: url('{{ Storage::url($homepageSettings->hero_image) }}')"></div>
    @endif

    <!-- Animated background particles -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white opacity-5 rounded-full animate-pulse"></div>
        <div class="absolute top-3/4 right-1/4 w-32 h-32 bg-orange-300 opacity-10 rounded-full animate-bounce"></div>
        <div class="absolute top-1/2 left-3/4 w-48 h-48 bg-green-300 opacity-5 rounded-full animate-pulse delay-1000">
        </div>
    </div>

    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 min-h-[700px] flex">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center py-20">
            <!-- Left Content -->
            <div class="text-white max-w-2xl space-y-8 animate-fade-in-up">
                <h1 class="text-5xl sm:text-6xl font-bold leading-tight">
                    {{ $homepageSettings->hero_title }}
                </h1>
                <p class="text-xl text-gray-100 leading-relaxed">
                    {{ $homepageSettings->hero_subtitle }}
                </p>
                <div class="flex flex-wrap gap-6">
                    <a href="#programs"
                        class="group relative bg-orange-400 hover:bg-orange-500 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                        <span class="relative z-10">{{ $homepageSettings->hero_cta_text }}</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        </div>
                    </a>
                    <a href="#about"
                        class="group border-2 border-white text-white hover:bg-white hover:text-[#2B593F] px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        Learn More
                    </a>
                </div>

                <!-- Trust indicators -->
                <div class="flex items-center space-x-8 pt-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-400">{{ $stats['students_trained'] }}</div>
                        <div class="text-sm text-gray-300">Students Trained</div>
                    </div>
                    <div class="w-px h-12 bg-gray-400"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-400">{{ $stats['programs'] }}</div>
                        <div class="text-sm text-gray-300">Programs Available</div>
                    </div>
                    <div class="w-px h-12 bg-gray-400"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-400">95%</div>
                        <div class="text-sm text-gray-300">Success Rate</div>
                    </div>
                </div>
            </div>

            <!-- Right Content - Enhanced Stats Card -->
            <div class="relative animate-fade-in-right">
                <div
                    class="bg-white/10 backdrop-blur-md rounded-2xl overflow-hidden shadow-2xl border border-white/20 hover:transform hover:scale-105 transition-all duration-500">
                    <div
                        class="h-72 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center relative overflow-hidden">
                        <img src="{{ asset('images/students.jpg') }}" alt="Students"
                            class="w-full h-full object-cover transition-transform duration-700 hover:scale-110"
                            onerror="this.src='{{ asset('images/default-course.jpg') }}'; this.onerror=null;">
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent">
                        </div>
                        <!-- Badge -->
                        <div
                            class="absolute top-4 right-4 bg-orange-400 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            ⭐ Top Rated
                        </div>
                    </div>
                    <div class="grid grid-cols-2 divide-x divide-white/20">
                        <div class="p-8 text-center group hover:bg-white/5 transition-colors">
                            <div
                                class="text-4xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">
                                {{ $stats['students_trained'] }}
                            </div>
                            <div class="text-sm text-gray-300">Students Trained</div>
                        </div>
                        <div class="p-8 text-center group hover:bg-white/5 transition-colors">
                            <div
                                class="text-4xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">
                                {{ $stats['programs'] }}
                            </div>
                            <div class="text-sm text-gray-300">Programs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <a href="#programs" class="text-white hover:text-orange-400 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3">
                </path>
            </svg>
        </a>
    </div>
</section>

<!-- Training Programs Section -->
<section id="programs" class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 space-y-4">
            <h2 class="text-4xl font-bold text-gray-900 animate-fade-in">Training Programs</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Comprehensive training programs designed to meet the diverse needs of Nairobi County residents
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-[#2B593F] to-orange-400 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($trainingPrograms as $index => $program)
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-2 animate-fade-in-up"
                style="animation-delay: {{ $index * 100 }}ms">
                <!-- Enhanced Image Container -->
                <div class="relative w-full pb-[75%] overflow-hidden">
                    <img src="{{ asset($program->banner ?? 'images/default-program.jpg') }}" alt="{{ $program->title }}"
                        class="absolute inset-0 w-full h-full object-cover bg-gray-100 transition-transform duration-700 group-hover:scale-110"
                        onerror="this.src='{{ asset('images/default-program.jpg') }}'; this.onerror=null;">
                    <!-- Gradient overlay -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <!-- Category badge -->
                    @if($program->category)
                    <div
                        class="absolute top-4 left-4 bg-[#2B593F] text-white px-3 py-1 rounded-full text-xs font-semibold">
                        {{ $program->category->name }}
                    </div>
                    @endif
                    <!-- Popular badge -->
                    @if($index < 2) <div
                        class="absolute top-4 right-4 bg-orange-400 text-white px-2 py-1 rounded-full text-xs font-semibold">
                        🔥 Popular
                </div>
                @endif
            </div>

            <div class="p-6 space-y-4">
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-[#2B593F] transition-colors">
                    {{ $program->title }}
                </h3>
                <p class="text-sm text-gray-600 line-clamp-3">
                    {{ strip_tags(Str::limit($program->description, 120)) }}
                </p>

                <!-- Program details -->
                <div class="flex flex-wrap gap-3 text-xs">
                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $program->period }}
                    </span>
                    @if($program->total_fee > 0)
                    <span
                        class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full font-semibold">
                        KSh {{ number_format($program->total_fee, 0) }}
                    </span>
                    @endif
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <a href="{{ route('programs.show', $program) }}"
                        class="block w-full text-center bg-[#2B593F] hover:bg-[#1E4230] text-white py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                        Explore Program
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-4 text-center py-16">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Programs Available</h3>
            <p class="text-gray-500">Check back soon for exciting training programs!</p>
        </div>
        @endforelse
    </div>
    </div>
</section>

<!-- Featured Courses Section -->
<section class="py-20 bg-white" id="courses">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 space-y-4">
            <h2 class="text-4xl font-bold text-gray-900">Featured Program Courses</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Popular courses that are making a difference in our community
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-orange-400 to-[#2B593F] mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredCourses as $index => $course)
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-2 border border-gray-100 hover:border-[#2B593F]/20 animate-fade-in-up"
                style="animation-delay: {{ $index * 100 }}ms">
                <div class="relative w-full pb-[60%] overflow-hidden">
                    <img src="{{ asset($course->thumbnail ?? 'images/default-course.jpg') }}" alt="{{ $course->title }}"
                        class="absolute inset-0 w-full h-full object-cover bg-gray-100 transition-transform duration-700 group-hover:scale-110"
                        onerror="this.src='{{ asset('images/default-course.jpg') }}'; this.onerror=null;">
                    <!-- Course level badge -->
                    <div
                        class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-xs font-semibold">
                        {{ $course->category->name ?? 'General' }}
                    </div>
                    <!-- Rating -->
                    <div
                        class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm text-gray-800 px-2 py-1 rounded-full text-xs font-semibold flex items-center">
                        ⭐ 4.8
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <h3
                        class="text-lg font-bold text-gray-900 group-hover:text-[#2B593F] transition-colors line-clamp-2">
                        {{ $course->title }}
                    </h3>

                    <!-- Course metrics -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Price:</span>
                            <span class="font-bold {{ $course->price === null ? 'text-green-600' : 'text-gray-900' }}">
                                {{ $course->formatted_price }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Duration:</span>
                            <span class="text-gray-900">{{ $course->duration_text }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Students:</span>
                            <span class="text-gray-900 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                                {{ $course->enrollment_count }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <a href="{{ route('courses.show', $course) }}"
                            class="block w-full text-center bg-gradient-to-r from-[#2B593F] to-[#1E4230] hover:from-[#1E4230] hover:to-[#0F2419] text-white py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                            View Course
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Pricing Packages Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 space-y-4">
            <h2 class="text-4xl font-bold text-gray-900 animate-fade-in">Choose Your Plan</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Select the perfect training package that fits your learning goals and budget.
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-[#2B593F] to-orange-400 mx-auto rounded-full"></div>
        </div>

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

<!-- Enhanced Testimonials Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-gray-100" id="testimonials">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 space-y-4">
            <h2 class="text-4xl font-bold text-gray-900">What Our Students Say</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Real stories from community members who transformed their lives
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-[#2B593F] to-orange-400 mx-auto rounded-full"></div>
        </div>

        <!-- Modern Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $testimonials = [
            [
            'name' => 'Grace Wanjiku',
            'role' => 'Business Program Graduate',
            'content' => 'The business training program helped me start my own tailoring business. I now employ 5 people
            from my community!',
            'rating' => 5,
            'image' => 'avatar1.jpg'
            ],
            [
            'name' => 'John Mwangi',
            'role' => 'IT Skills Graduate',
            'content' => 'Thanks to the computer skills training, I landed a job at a tech company. The practical
            approach was amazing!',
            'rating' => 5,
            'image' => 'avatar2.jpg'
            ],
            [
            'name' => 'Mary Akinyi',
            'role' => 'Healthcare Training Graduate',
            'content' => 'The healthcare training gave me confidence and skills. I\'m now working as a community health
            worker helping families.',
            'rating' => 5,
            'image' => 'avatar3.jpg'
            ]
            ];
            @endphp

            @foreach($testimonials as $index => $testimonial)
            <div class="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-fade-in-up"
                style="animation-delay: {{ $index * 200 }}ms">
                <!-- Quote icon -->
                <div class="text-[#2B593F] mb-6">
                    <svg class="w-8 h-8 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z" />
                    </svg>
                </div>

                <p class="text-gray-700 mb-6 leading-relaxed">{{ $testimonial['content'] }}</p>

                <!-- Rating -->
                <div class="flex items-center mb-6">
                    @for($i = 1; $i <= 5; $i++) <svg
                        class="w-5 h-5 {{ $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300' }}"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endfor
                </div>

                <!-- User info -->
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-[#2B593F] to-[#1E4230] rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                        {{ substr($testimonial['name'], 0, 1) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $testimonial['name'] }}</h4>
                        <p class="text-sm text-gray-600">{{ $testimonial['role'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Enhanced CTA Section -->
<section class="relative py-20 bg-gradient-to-r from-[#2B593F] via-[#1E4230] to-[#0F2419] overflow-hidden">
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=" 60" height="60"
            viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg" %3E%3Cg fill="none" fill-rule="evenodd" %3E%3Cg
            fill="%23ffffff" fill-opacity="0.1" %3E%3Ccircle cx="30" cy="30" r="4" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E')">
        </div>
    </div>

    <div class="relative container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center text-white space-y-8">
            <h2 class="text-5xl font-bold mb-6 animate-fade-in">Ready to Start Your Learning Journey?</h2>
            <p class="text-2xl mb-10 text-gray-100 animate-fade-in" style="animation-delay: 200ms">
                Join thousands of community members who have transformed their lives through our training programs
            </p>

            <!-- Enhanced CTA buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-6 animate-fade-in" style="animation-delay: 400ms">
                <a href="{{ route('register') }}"
                    class="group relative bg-orange-400 hover:bg-orange-500 text-white px-10 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    <span class="relative z-10 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                        Register Now
                    </span>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                </a>
                <a href="#programs"
                    class="group border-2 border-white text-white hover:bg-white hover:text-[#2B593F] px-10 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        View Programs
                    </span>
                </a>
            </div>

            <!-- Trust indicators -->
            <div class="pt-12 border-t border-white/20">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div>
                        <div class="text-3xl font-bold text-orange-400">100+</div>
                        <div class="text-sm text-gray-300">Certified Instructors</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-orange-400">24/7</div>
                        <div class="text-sm text-gray-300">Student Support</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-orange-400">95%</div>
                        <div class="text-sm text-gray-300">Job Placement Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom CSS for animations -->
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-out;
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }

    .animate-fade-in-right {
        animation: fadeInRight 1s ease-out;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Enhanced hover effects */
    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }

    /* Glassmorphism effect */
    .backdrop-blur-md {
        backdrop-filter: blur(12px);
    }

    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
</style>

<!-- Add scroll animations script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all fade-in-up elements
    document.querySelectorAll('.animate-fade-in-up').forEach(el => {
        observer.observe(el);
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection
