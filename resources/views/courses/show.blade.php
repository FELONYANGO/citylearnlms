@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-[#2B593F] via-[#1E4230] to-[#0F2419] overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full animate-pulse"></div>
            <div class="absolute top-1/2 right-20 w-20 h-20 bg-orange-300 rounded-full animate-bounce"></div>
            <div class="absolute bottom-10 left-1/3 w-16 h-16 bg-green-300 rounded-full animate-pulse delay-1000"></div>
        </div>

        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Course Info -->
                <div class="text-white">
                    <!-- Course Category Badge -->
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                        <i class="fas fa-tag mr-2"></i>
                        <span class="text-sm font-medium">{{ $course->category->name }}</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                        {{ $course->title }}
                    </h1>

                    <p class="text-xl text-white/90 mb-8 leading-relaxed">
                        {{ $course->description }}
                    </p>

                    <!-- Course Stats -->
                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4">
                                <div class="text-2xl font-bold">{{ $course->curriculumItems->count() }}</div>
                                <div class="text-sm text-white/80">Lessons</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4">
                                <div class="text-2xl font-bold">{{ $course->enrollment_count }}</div>
                                <div class="text-sm text-white/80">Students</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4">
                                <div class="text-2xl font-bold">{{ $course->level }}</div>
                                <div class="text-sm text-white/80">Level</div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructor Info -->
                    <div class="flex items-center bg-white/10 backdrop-blur-sm rounded-2xl p-4">
                        <img src="{{ asset($course->creator->avatar ?? 'images/default-avatar.jpg') }}"
                            alt="{{ $course->creator->name }}" class="w-12 h-12 rounded-full mr-4 ring-2 ring-white/20">
                        <div>
                            <p class="font-semibold">{{ $course->creator->name }}</p>
                            <p class="text-sm text-white/80">Course Instructor</p>
                        </div>
                    </div>
                </div>

                <!-- Course Image/Video -->
                <div class="relative">
                    <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 text-center">
                        @if($course->image)
                        <img src="{{ asset($course->image) }}" alt="{{ $course->title }}"
                            class="w-full h-64 object-cover rounded-2xl mb-6 shadow-xl">
                        @else
                        <div
                            class="w-full h-64 bg-gradient-to-br from-white/20 to-white/10 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-play-circle text-6xl text-white/60"></i>
                        </div>
                        @endif

                        <!-- Special Offer Badge -->
                        @if($course->is_featured)
                        <div class="inline-flex items-center bg-orange-500 text-white px-4 py-2 rounded-full mb-4">
                            <i class="fas fa-star mr-2"></i>
                            <span class="font-semibold">20% OFF - Limited Time!</span>
                        </div>
                        @endif

                        <!-- Price Display -->
                        <div class="mb-6">
                            @if($course->price == 0)
                            <div class="text-4xl font-bold text-white">Free Course</div>
                            @else
                            @if($course->is_featured)
                            <div class="text-lg text-white/60 line-through">KSh {{ number_format($course->price, 2) }}
                            </div>
                            <div class="text-4xl font-bold text-white">KSh {{ number_format($course->price * 0.8, 2) }}
                            </div>
                            @else
                            <div class="text-4xl font-bold text-white">KSh {{ number_format($course->price, 2) }}</div>
                            @endif
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-4">
                            @auth
                            @php
                            $user = Auth::user();
                            $isEnrolled = $user->enrollments()
                            ->where('course_id', $course->id)
                            ->whereNotIn('status', ['cancelled', 'expired'])
                            ->exists();
                            $isAdmin = $user->hasRole('admin');
                            $isCreator = $user->id === $course->created_by;
                            @endphp

                            @if($isEnrolled)
                            @if($userState['hasValidPayment'])
                            <a href="{{ route('courses.learn', $course) }}"
                                class="block w-full bg-white text-[#2B593F] py-4 px-6 rounded-2xl font-semibold text-lg hover:bg-gray-100 transition-all duration-300 shadow-lg">
                                <i class="fas fa-play mr-2"></i>
                                Continue Learning
                            </a>
                            @else
                            <a href="{{ route('payments.checkout', $course) }}"
                                class="block w-full bg-orange-500 text-white py-4 px-6 rounded-2xl font-semibold text-lg hover:bg-orange-600 transition-all duration-300 shadow-lg">
                                <i class="fas fa-credit-card mr-2"></i>
                                Complete Payment
                            </a>
                            @endif
                            @elseif($isAdmin || $isCreator)
                            <a href="{{ route('filament.admin.resources.courses.edit', $course) }}"
                                class="block w-full bg-blue-500 text-white py-4 px-6 rounded-2xl font-semibold text-lg hover:bg-blue-600 transition-all duration-300 shadow-lg">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Course
                            </a>
                            @else
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-white text-[#2B593F] py-4 px-6 rounded-2xl font-semibold text-lg hover:bg-gray-100 transition-all duration-300 shadow-lg">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    Enroll Now
                                </button>
                            </form>
                            @endif
                            @else
                            <a href="{{ route('login') }}"
                                class="block w-full bg-white text-[#2B593F] py-4 px-6 rounded-2xl font-semibold text-lg hover:bg-gray-100 transition-all duration-300 shadow-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login to Enroll
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Course Content -->
            <div class="lg:col-span-2">
                <!-- Tab Navigation -->
                <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">
                    <div class="border-b border-gray-200">
                        <nav class="flex" id="course-tabs">
                            <button
                                class="tab-button active px-8 py-4 text-sm font-medium text-[#2B593F] border-b-2 border-[#2B593F] bg-green-50"
                                onclick="showTab('overview')">
                                <i class="fas fa-info-circle mr-2"></i>
                                Overview
                            </button>
                            <button class="tab-button px-8 py-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                                onclick="showTab('curriculum')">
                                <i class="fas fa-list mr-2"></i>
                                Curriculum ({{ $course->curriculumItems->count() }})
                            </button>
                            <button class="tab-button px-8 py-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                                onclick="showTab('reviews')">
                                <i class="fas fa-star mr-2"></i>
                                Reviews
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-8">
                        <!-- Overview Tab -->
                        <div id="overview-tab" class="tab-content">
                            <div class="prose max-w-none">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">About This Course</h2>
                                <p class="text-gray-600 text-lg leading-relaxed mb-8">{{ $course->description }}</p>

                                @if($course->objectives)
                                <div class="mb-8">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-target text-[#2B593F] mr-2"></i>
                                        What You'll Learn
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($course->objectives as $objective)
                                        <div class="flex items-start bg-green-50 p-4 rounded-xl">
                                            <div
                                                class="flex-shrink-0 w-6 h-6 bg-[#2B593F] rounded-full flex items-center justify-center mr-3 mt-1">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <span class="text-gray-700">{{ $objective }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if($course->prerequisites)
                                <div class="mb-8">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-clipboard-list text-orange-500 mr-2"></i>
                                        Prerequisites
                                    </h3>
                                    <div class="space-y-3">
                                        @foreach($course->prerequisites as $prerequisite)
                                        <div class="flex items-start bg-orange-50 p-4 rounded-xl">
                                            <div
                                                class="flex-shrink-0 w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center mr-3 mt-1">
                                                <i class="fas fa-exclamation text-white text-xs"></i>
                                            </div>
                                            <span class="text-gray-700">{{ $prerequisite }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Curriculum Tab -->
                        <div id="curriculum-tab" class="tab-content hidden">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Course Curriculum</h2>
                            <div class="space-y-4">
                                @foreach($course->curriculumItems as $index => $item)
                                <div class="bg-gray-50 rounded-xl p-6 hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-[#2B593F] rounded-full flex items-center justify-center text-white font-bold mr-4">
                                                {{ $index + 1 }}
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $item->title }}</h3>
                                                <p class="text-sm text-gray-600">{{ $item->description }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            @if($item->content_type === 'video')
                                            <i class="fas fa-play-circle mr-1"></i>
                                            Video
                                            @elseif($item->content_type === 'document')
                                            <i class="fas fa-file-alt mr-1"></i>
                                            Document
                                            @else
                                            <i class="fas fa-book mr-1"></i>
                                            Content
                                            @endif
                                        </div>
                                    </div>

                                    @if($item->quiz)
                                    <div class="mt-4 pl-14">
                                        <div class="bg-blue-50 rounded-lg p-3 border-l-4 border-blue-400">
                                            <div class="flex items-center">
                                                <i class="fas fa-question-circle text-blue-500 mr-2"></i>
                                                <span class="text-sm font-medium text-blue-700">Quiz: {{
                                                    $item->quiz->title }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div id="reviews-tab" class="tab-content hidden">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Student Reviews</h2>
                            <div class="text-center py-12">
                                <div class="text-6xl text-gray-300 mb-4">
                                    <i class="fas fa-star"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Reviews Yet</h3>
                                <p class="text-gray-500">Be the first to review this course after enrolling.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Course Features -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">This Course Includes</h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-play-circle text-[#2B593F] mr-3"></i>
                            <span>{{ $course->curriculumItems->count() }} video lessons</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-certificate text-[#2B593F] mr-3"></i>
                            <span>Certificate of completion</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-mobile-alt text-[#2B593F] mr-3"></i>
                            <span>Mobile and desktop access</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-infinity text-[#2B593F] mr-3"></i>
                            <span>Lifetime access</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-headset text-[#2B593F] mr-3"></i>
                            <span>Instructor support</span>
                        </div>
                    </div>
                </div>

                <!-- Course Details -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Details</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Level</span>
                            <span class="font-semibold text-gray-900">{{ $course->level }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-semibold text-gray-900">{{ $course->duration_text }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Type</span>
                            <span class="font-semibold text-gray-900">{{ $course->type }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Language</span>
                            <span class="font-semibold text-gray-900">English</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Students</span>
                            <span class="font-semibold text-gray-900">{{ $course->enrollment_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'text-[#2B593F]', 'border-[#2B593F]', 'bg-green-50');
        button.classList.add('text-gray-500');
    });

    // Show the selected tab content
    document.getElementById(tabName + '-tab').classList.remove('hidden');

    // Add active class to the clicked tab button
    event.target.classList.add('active', 'text-[#2B593F]', 'border-[#2B593F]', 'bg-green-50');
    event.target.classList.remove('text-gray-500');
}

// Add smooth scrolling and animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all course content elements
    document.querySelectorAll('.bg-white, .bg-gray-50').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
</script>
@endsection