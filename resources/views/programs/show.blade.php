@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-[#2B593F] via-[#1E4230] to-[#0F2419] overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-16 left-16 w-40 h-40 bg-white rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-24 w-24 h-24 bg-orange-300 rounded-full animate-bounce"></div>
            <div class="absolute bottom-16 left-1/4 w-20 h-20 bg-green-300 rounded-full animate-pulse delay-1000"></div>
            <div class="absolute top-2/3 right-1/3 w-32 h-32 bg-blue-300 rounded-full animate-pulse delay-500"></div>
        </div>

        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center max-w-4xl mx-auto">
                <!-- Program Badge -->
                <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-6 py-3 mb-8">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    <span class="text-lg font-medium text-white">Training Program</span>
                </div>

                <h1 class="text-5xl md:text-6xl font-bold text-white mb-8 leading-tight">
                    {{ $program->title }}
                </h1>

                <p class="text-xl text-white/90 mb-12 leading-relaxed max-w-3xl mx-auto">
                    {!! strip_tags($program->description) !!}
                </p>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6">
                        <div class="text-3xl font-bold text-white">{{ $program->courses->count() }}</div>
                        <div class="text-sm text-white/80">Courses</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6">
                        <div class="text-3xl font-bold text-white">{{ $program->enrollments->count() }}</div>
                        <div class="text-sm text-white/80">Students</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6">
                        <div class="text-3xl font-bold text-white">{{ $program->level }}</div>
                        <div class="text-sm text-white/80">Level</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6">
                        <div class="text-3xl font-bold text-white">{{ $successRate }}%</div>
                        <div class="text-sm text-white/80">Success Rate</div>
                    </div>
                </div>

                <!-- Program Info Card -->
                <div class="max-w-md mx-auto bg-white/15 backdrop-blur-sm rounded-3xl p-8 border border-white/20">
                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">{{ $program->period }}</div>
                            <div class="text-sm text-white/80">Duration</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-white">{{ ucfirst($program->status) }}</div>
                            <div class="text-sm text-white/80">Status</div>
                        </div>
                    </div>

                    <!-- Price Display -->
                    <div class="mb-8">
                        <div class="text-4xl font-bold text-white mb-2">
                            KSh {{ number_format($program->total_fee, 2) }}
                        </div>
                        @if($program->exam_fee > 0)
                        <div class="text-sm text-white/80">
                            Includes KSh {{ number_format($program->exam_fee, 2) }} exam fee
                        </div>
                        @endif
                    </div>

                    <!-- Action Button -->
                    @auth
                    @if($userState['isEnrolled'])
                    @if($userState['hasValidPayment'])
                    <a href="{{ route('programs.learn', $program) }}"
                        class="block w-full bg-white text-[#2B593F] py-4 px-6 rounded-2xl font-semibold text-lg hover:bg-gray-100 transition-all duration-300 shadow-lg">
                        <i class="fas fa-play mr-2"></i>
                        All Courses
                    </a>
                    @else
                    <form action="{{ route('programs.enroll', $program) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full bg-orange-500 text-white py-4 px-6 rounded-2xl font-semibold text-lg hover:bg-orange-600 transition-all duration-300 shadow-lg">
                            <i class="fas fa-credit-card mr-2"></i>
                            Complete Payment
                        </button>
                    </form>
                    @endif
                    @elseif($userState['isAdmin'] || $userState['isTrainer'])
                    <a href="{{ route('filament.admin.resources.training-programs.edit', $program) }}"
                        class="block w-full bg-blue-500 text-white py-4 px-6 rounded-2xl font-semibold text-lg hover:bg-blue-600 transition-all duration-300 shadow-lg">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Program
                    </a>
                    @else
                    <form action="{{ route('programs.enroll', $program) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full bg-white text-[#2B593F] py-4 px-6 rounded-2xl font-semibold text-lg hover:bg-gray-100 transition-all duration-300 shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i>
                            Enroll Now
                        </button>
                    </form>
                    @endif
                    @else
                    <a href="{{ route('login', ['intended' => url()->current()]) }}"
                        class="block w-full bg-white text-[#2B593F] py-4 px-6 rounded-2xl font-semibold text-lg hover:bg-gray-100 transition-all duration-300 shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login to Enroll
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Program Content -->
            <div class="lg:col-span-2">
                <!-- Tab Navigation -->
                <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">
                    <div class="border-b border-gray-200">
                        <nav class="flex" id="program-tabs">
                            <button
                                class="tab-button active px-8 py-4 text-sm font-medium text-[#2B593F] border-b-2 border-[#2B593F] bg-green-50"
                                onclick="showTab('overview')">
                                <i class="fas fa-info-circle mr-2"></i>
                                Overview
                            </button>
                            <button class="tab-button px-8 py-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                                onclick="showTab('curriculum')">
                                <i class="fas fa-list mr-2"></i>
                                Curriculum
                            </button>
                            <button class="tab-button px-8 py-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                                onclick="showTab('instructor')">
                                <i class="fas fa-user-tie mr-2"></i>
                                Instructor
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-8">
                        <!-- Overview Tab -->
                        <div id="overview-tab" class="tab-content">
                            <div class="prose max-w-none">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6">About This Program</h2>
                                <div class="text-gray-600 text-lg leading-relaxed mb-8">{!! $program->description !!}
                                </div>

                                @if($program->objectives && is_array($program->objectives))
                                <div class="mb-8">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-target text-[#2B593F] mr-2"></i>
                                        Learning Objectives
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($program->objectives as $objective)
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

                                @if($program->prerequisites && is_array($program->prerequisites))
                                <div class="mb-8">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-clipboard-list text-orange-500 mr-2"></i>
                                        Prerequisites
                                    </h3>
                                    <div class="space-y-3">
                                        @foreach($program->prerequisites as $prerequisite)
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

                                <!-- Assessment Method -->
                                @if($program->assessment_method && is_array($program->assessment_method))
                                <div class="mb-8">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                                        <i class="fas fa-clipboard-check text-blue-500 mr-2"></i>
                                        Assessment Methods
                                    </h3>
                                    <div class="space-y-3">
                                        @foreach($program->assessment_method as $method)
                                        <div class="flex items-start bg-blue-50 p-4 rounded-xl">
                                            <div
                                                class="flex-shrink-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 mt-1">
                                                <i class="fas fa-check text-white text-xs"></i>
                                            </div>
                                            <span class="text-gray-700">{{ $method }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if($program->certification)
                                <div class="bg-gradient-to-r from-[#2B593F] to-[#1E4230] p-6 rounded-xl text-white">
                                    <h3 class="text-xl font-semibold mb-3">
                                        <i class="fas fa-certificate mr-2"></i>
                                        Certification
                                    </h3>
                                    <p>{{ $program->certification }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Curriculum Tab -->
                        <div id="curriculum-tab" class="tab-content hidden">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Program Structure</h2>

                            @php
                            $levelNames = [
                            1 => 'Foundation',
                            2 => 'Intermediate',
                            3 => 'Advanced',
                            4 => 'Expert'
                            ];
                            @endphp

                            <div class="space-y-8">
                                @foreach($program->courses->take(2) as $course)
                                <div class="bg-white rounded-xl p-6 hover:bg-gray-50 transition-all duration-300">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $course->title }}</h3>
                                        <a href="{{ route('courses.show', $course) }}"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-[#2B593F] text-white rounded-xl hover:bg-[#1E4230] transition-all duration-300">
                                            <i class="fas fa-play mr-2"></i>
                                            Start
                                        </a>
                                    </div>
                                    <div class="text-gray-600">{!! strip_tags($course->description) !!}</div>
                                    <div class="mt-4 flex items-center text-sm text-gray-500">
                                        <i class="fas fa-book-open mr-2"></i>
                                        <span>{{ $course->curriculumItems->count() }} lessons</span>
                                        <i class="fas fa-users mx-2"></i>
                                        <span>{{ $course->enrollments->count() }} students</span>
                                    </div>
                                </div>
                                @endforeach

                                @if($program->courses->count() > 2)
                                <div class="text-center mt-8">
                                    <a href="{{ route('programs.learn', $program) }}"
                                        class="inline-flex items-center justify-center px-8 py-3 bg-white text-[#2B593F] rounded-xl border-2 border-[#2B593F] hover:bg-[#2B593F] hover:text-white transition-all duration-300">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        All Program Courses
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Instructor Tab -->
                        <div id="instructor-tab" class="tab-content hidden">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Meet Your Instructor</h2>

                            @if($program->trainer)
                            <div class="bg-gradient-to-r from-[#2B593F] to-[#1E4230] rounded-2xl p-8 text-white">
                                <div class="flex items-center mb-6">
                                    <img src="{{ asset($program->trainer->avatar ?? 'images/default-avatar.jpg') }}"
                                        alt="{{ $program->trainer->name }}"
                                        class="w-20 h-20 rounded-full mr-6 ring-4 ring-white/20">
                                    <div>
                                        <h3 class="text-2xl font-bold">{{ $program->trainer->name }}</h3>
                                        <p class="text-white/80 text-lg">{{ $program->trainer->title ?? 'Program
                                            Trainer' }}</p>
                                    </div>
                                </div>

                                @if($program->trainer->bio)
                                <p class="text-white/90 leading-relaxed">{{ $program->trainer->bio }}</p>
                                @endif
                            </div>
                            @endif

                            @if($program->organization)
                            <div class="mt-8 bg-white border border-gray-200 rounded-2xl p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                                    <i class="fas fa-building mr-2 text-[#2B593F]"></i>
                                    Organization
                                </h3>
                                <div class="flex items-center">
                                    <div
                                        class="w-16 h-16 bg-[#2B593F] rounded-full flex items-center justify-center mr-4">
                                        <i class="fas fa-building text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $program->organization->name }}</h4>
                                        <p class="text-gray-600">Training Provider</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Program Features -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">This Program Includes</h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-graduation-cap text-[#2B593F] mr-3"></i>
                            <span>{{ $program->courses->count() }} comprehensive courses</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-certificate text-[#2B593F] mr-3"></i>
                            <span>Professional certification</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-users text-[#2B593F] mr-3"></i>
                            <span>Expert instructor guidance</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-laptop text-[#2B593F] mr-3"></i>
                            <span>Online learning platform</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-headset text-[#2B593F] mr-3"></i>
                            <span>24/7 support</span>
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-mobile-alt text-[#2B593F] mr-3"></i>
                            <span>Mobile app access</span>
                        </div>
                    </div>
                </div>

                <!-- Program Details -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Program Details</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Duration</span>
                            <span class="font-semibold text-gray-900">{{ $program->period }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Level</span>
                            <span class="font-semibold text-gray-900">{{ $program->level }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Courses</span>
                            <span class="font-semibold text-gray-900">{{ $program->courses->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Students</span>
                            <span class="font-semibold text-gray-900">{{ $program->enrollments->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status</span>
                            <span class="font-semibold text-gray-900">{{ ucfirst($program->status) }}</span>
                        </div>
                        @if($program->exam_fee > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Exam Fee</span>
                            <span class="font-semibold text-gray-900">KSh {{ number_format($program->exam_fee, 2)
                                }}</span>
                        </div>
                        @endif
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

    // Observe all program content elements
    document.querySelectorAll('.bg-white, .bg-gray-50').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});
</script>
@endsection