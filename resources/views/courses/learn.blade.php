@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div x-data="{ sidebarOpen: true }" class="flex min-h-screen bg-gray-100">
    {{-- Mobile Close Button - Positioned relative to content --}}
    <div class="lg:hidden fixed top-4 right-4 z-50" x-show="sidebarOpen">
        <button @click="sidebarOpen = false"
            class="bg-green-600 text-white rounded-lg p-2 shadow-lg hover:bg-green-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Mobile Open Button - Only shows when sidebar is closed --}}
    <div class="lg:hidden fixed top-4 left-4 z-50" x-show="!sidebarOpen">
        <button @click="sidebarOpen = true"
            class="bg-white text-gray-600 rounded-lg p-2 shadow-lg hover:bg-gray-50 focus:outline-none border">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    {{-- Sidebar --}}
    <div x-show="sidebarOpen"
        class="fixed inset-0 lg:relative lg:inset-auto w-80 bg-white shadow-lg overflow-y-auto transition-transform duration-300 ease-in-out z-40 lg:z-0"
        :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
        {{-- Course Title with Toggle --}}
        <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-800">{{ $course->title }}</h2>
                <p class="text-sm text-gray-500">{{ $course->duration_text }}</p>

                {{-- Progress Indicator --}}
                @if(isset($navigationSequence) && count($navigationSequence) > 0)
                <div class="mt-2 flex items-center space-x-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        @php
                        $currentPos = 0;
                        if (request('quiz')) {
                        $quizId = is_object(request('quiz')) ? request('quiz')->id : request('quiz');
                        foreach ($navigationSequence as $index => $navItem) {
                        if ($navItem['type'] === 'quiz' && $navItem['id'] == $quizId) {
                        $currentPos = $index + 1;
                        break;
                        }
                        }
                        } elseif (request('item')) {
                        foreach ($navigationSequence as $index => $navItem) {
                        if ($navItem['type'] === 'curriculum_item' && $navItem['id'] == request('item')) {
                        $currentPos = $index + 1;
                        break;
                        }
                        }
                        } else {
                        $currentPos = 1;
                        }
                        $progressPercentage = ($currentPos / count($navigationSequence)) * 100;
                        @endphp
                        <div class="bg-green-500 h-2 rounded-full transition-all duration-300" id="course-progress-bar"
                            style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <span class="text-xs text-gray-500 whitespace-nowrap" id="course-progress-text">
                        {{ $currentPos }} / {{ count($navigationSequence) }}
                    </span>
                </div>
                @endif
            </div>
            {{-- Desktop Sidebar Toggle - Always Visible --}}
            <button @click="sidebarOpen = !sidebarOpen"
                class="hidden lg:flex p-2 hover:bg-gray-200 rounded-lg focus:outline-none">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    x-show="sidebarOpen">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    x-show="!sidebarOpen">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        {{-- Curriculum Items with Quizzes --}}
        <div class="p-4">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Course Content</h3>
            <div class="space-y-3">
                @foreach($curriculumItems as $item)
                <div class="space-y-2">
                    {{-- Content Item --}}
                    <a href="{{ route('courses.learn', ['course' => $course, 'item' => $item->id]) }}"
                        @class([ 'flex items-center p-3 rounded-lg transition-all duration-200 group relative'
                        , 'bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-sm'=> $currentItem &&
                        $currentItem->id === $item->id && !$currentQuiz,
                        'hover:bg-gray-50 border border-transparent' => !($currentItem && $currentItem->id === $item->id
                        && !$currentQuiz),
                        'bg-green-50 border-green-200' => in_array($item->id, $completions) && !($currentItem &&
                        $currentItem->id === $item->id && !$currentQuiz)
                        ])>

                        {{-- Navigation Order Number --}}
                        <div class="mr-3 flex-shrink-0">
                            @php
                            $itemOrder = 0;
                            if (isset($navigationSequence)) {
                            foreach ($navigationSequence as $index => $navItem) {
                            if ($navItem['type'] === 'curriculum_item' && $navItem['id'] == $item->id) {
                            $itemOrder = $index + 1;
                            break;
                            }
                            }
                            }
                            @endphp
                            <div
                                class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-semibold text-gray-600">
                                {{ $itemOrder }}
                            </div>
                        </div>

                        {{-- Completion Status --}}
                        <div class="mr-3 flex-shrink-0">
                            @if(in_array($item->id, $completions))
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            @else
                            <div
                                class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center group-hover:bg-gray-300 transition-colors">
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            </div>
                            @endif
                        </div>

                        {{-- Icon based on content type --}}
                        <div class="mr-3 flex-shrink-0">
                            @if($item->content_type === 'video' || ($item->content_type === 'file' && $item->file_url &&
                            in_array(strtolower(pathinfo($item->file_url, PATHINFO_EXTENSION)), ['mp4', 'webm', 'ogg',
                            'avi', 'mov'])))
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            @elseif($item->content_type === 'file' && $item->file_url &&
                            in_array(strtolower(pathinfo($item->file_url, PATHINFO_EXTENSION)), ['pdf']))
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @elseif($item->content_type === 'file' && $item->file_url &&
                            in_array(strtolower(pathinfo($item->file_url, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png',
                            'gif', 'webp', 'svg']))
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @elseif($item->content_type === 'file' && $item->file_url &&
                            in_array(strtolower(pathinfo($item->file_url, PATHINFO_EXTENSION)), ['mp3', 'wav', 'ogg',
                            'aac']))
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                                </svg>
                            </div>
                            @elseif($item->content_type === 'file' && $item->file_url &&
                            in_array(strtolower(pathinfo($item->file_url, PATHINFO_EXTENSION)), ['doc', 'docx', 'xls',
                            'xlsx', 'ppt', 'pptx']))
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            @elseif($item->content_type === 'file')
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </div>
                            @else
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ $item->title }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ ucfirst($item->content_type) }}</p>
                        </div>
                    </a>

                    {{-- Associated Quiz if exists --}}
                    @if($item->quiz)
                    @php
                    $quizAttempt = $quizAttempts->get($item->quiz->id);
                    $quizCompleted = $quizAttempt && $quizAttempt->status === 'completed';
                    $quizOrder = 0;
                    if (isset($navigationSequence)) {
                    foreach ($navigationSequence as $index => $navItem) {
                    if ($navItem['type'] === 'quiz' && $navItem['id'] == $item->quiz->id) {
                    $quizOrder = $index + 1;
                    break;
                    }
                    }
                    }
                    @endphp

                    {{-- Connecting Line --}}
                    <div class="ml-6 flex items-center">
                        <div class="w-6 h-0.5 bg-gray-300"></div>
                        <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                        <div class="w-6 h-0.5 bg-gray-300"></div>
                    </div>

                    <a href="{{ route('courses.quiz', ['course' => $course, 'quiz' => $item->quiz->id]) }}"
                        @class([ 'flex items-center p-3 ml-6 rounded-lg transition-all duration-200 group relative'
                        , 'bg-green-50 text-green-700 border border-green-200 shadow-sm'=> $currentQuiz &&
                        $currentQuiz->id === $item->quiz->id,
                        'hover:bg-gray-50 border border-transparent' => !($currentQuiz && $currentQuiz->id ===
                        $item->quiz->id),
                        'bg-blue-50 border-blue-200' => $quizCompleted && !($currentQuiz && $currentQuiz->id ===
                        $item->quiz->id)
                        ])>

                        {{-- Navigation Order Number --}}
                        <div class="mr-3 flex-shrink-0">
                            <div
                                class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-xs font-semibold text-gray-600">
                                {{ $quizOrder }}
                            </div>
                        </div>

                        {{-- Quiz Completion Status --}}
                        <div class="mr-3 flex-shrink-0">
                            @if($quizCompleted)
                            <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            @else
                            <div
                                class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center group-hover:bg-gray-300 transition-colors">
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            </div>
                            @endif
                        </div>

                        <div class="mr-3 flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ $item->quiz->title }}</p>
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                <span>Quiz</span>
                                @if($quizAttempt)
                                <span>•</span>
                                <span class="text-blue-600 font-medium">{{ $quizAttempt->score }}%</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Final Exam --}}
        @if($exam)
        <div class="p-4 border-t">
            <a href="{{ route('courses.exam', ['course' => $course, 'exam' => $exam]) }}"
                class="flex items-center p-3 rounded-lg hover:bg-gray-50">
                <div class="mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium">Final Exam</p>
                </div>
            </a>
        </div>
        @endif

        {{-- Certificate --}}
        @if($certificate)
        <div class="p-4 border-t">
            <a href="{{ route('courses.certificate.download', ['course' => $course]) }}"
                class="flex items-center p-3 rounded-lg hover:bg-gray-50 text-green-600">
                <div class="mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium">Download Certificate</p>
                </div>
            </a>
        </div>
        @endif
    </div>

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col min-h-screen">
        @if($currentExam)
        @include('courses.partials.exam', ['exam' => $currentExam])
        @elseif($currentQuiz)
        @include('courses.partials.quiz', ['quiz' => $currentQuiz])
        @elseif($currentItem)
        {{-- Header --}}
        <div class="bg-white shadow-sm">
            <div class="px-6 py-4 flex items-center justify-between">
                <h1 class="text-xl font-semibold text-gray-800">{{ $currentItem->title }}</h1>
                {{-- Desktop Sidebar Toggle Button - Shows when sidebar is closed --}}
                <button @click="sidebarOpen = true"
                    class="hidden lg:flex p-2 hover:bg-gray-100 rounded-lg focus:outline-none" x-show="!sidebarOpen">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Content Area with Flex Column --}}
        <div class="flex-1 flex flex-col">
            {{-- Main Content --}}
            <div class="flex-1 p-6">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                    {{-- Content based on type --}}
                    @switch($currentItem->content_type)
                    @case('video')
                    @if($currentItem->video_url)
                    <div class="video-container bg-gray-900 rounded-t-xl overflow-hidden">
                        @if($currentItem->isYouTubeVideo() && $currentItem->getYouTubeEmbedUrl())
                        {{-- YouTube Video --}}
                        <iframe src="{{ $currentItem->getYouTubeEmbedUrl() }}" class="w-full h-full" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                        @elseif(filter_var($currentItem->video_url, FILTER_VALIDATE_URL))
                        {{-- External video URL --}}
                        <video class="w-full h-full" controls>
                            <source src="{{ $currentItem->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        @else
                        {{-- Uploaded video file --}}
                        <video class="w-full h-full" controls>
                            <source src="{{ Storage::url($currentItem->video_url) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        @endif
                    </div>
                    @endif
                    @break

                    @case('file')
                    @if($currentItem->file_url)
                    @php
                    $fileExtension = pathinfo($currentItem->file_url, PATHINFO_EXTENSION);
                    $fileUrl = filter_var($currentItem->file_url, FILTER_VALIDATE_URL)
                    ? $currentItem->file_url
                    : Storage::url($currentItem->file_url);
                    @endphp

                    @if(in_array(strtolower($fileExtension), ['pdf']))
                    {{-- PDF File --}}
                    <div class="h-[600px] lg:h-[800px] xl:h-[900px] bg-gray-50">
                        <iframe src="{{ $fileUrl }}" class="w-full h-full rounded-t-xl" type="application/pdf">
                            <p>Your browser does not support PDFs.
                                <a href="{{ $fileUrl }}" target="_blank">Download the PDF</a>
                            </p>
                        </iframe>
                    </div>
                    @elseif(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                    {{-- Image File --}}
                    <div class="p-6 text-center">
                        <img src="{{ $fileUrl }}" alt="{{ $currentItem->title }}"
                            class="max-w-full h-auto mx-auto rounded-lg shadow-lg">
                    </div>
                    @elseif(in_array(strtolower($fileExtension), ['mp4', 'webm', 'ogg']))
                    {{-- Video File --}}
                    <div class="aspect-w-16 aspect-h-9 bg-gray-900">
                        <video class="w-full h-full rounded-t-xl" controls>
                            <source src="{{ $fileUrl }}" type="video/{{ $fileExtension }}">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    @elseif(in_array(strtolower($fileExtension), ['mp3', 'wav', 'ogg']))
                    {{-- Audio File --}}
                    <div class="p-6 text-center">
                        <audio controls class="w-full max-w-md mx-auto">
                            <source src="{{ $fileUrl }}" type="audio/{{ $fileExtension }}">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                    @elseif(in_array(strtolower($fileExtension), ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']))
                    {{-- Office Documents --}}
                    <div class="p-8 text-center">
                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                            <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $currentItem->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ strtoupper($fileExtension) }} Document</p>
                            <a href="{{ $fileUrl }}" target="_blank" download
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download File
                            </a>
                        </div>
                    </div>
                    @else
                    {{-- Generic File Download --}}
                    <div class="p-8 text-center">
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $currentItem->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ strtoupper($fileExtension) }} File</p>
                            <a href="{{ $fileUrl }}" target="_blank" download
                                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download File
                            </a>
                        </div>
                    </div>
                    @endif
                    @endif
                    @break

                    @default
                    {{-- Beautiful Text Content Card --}}
                    <div class="p-8">
                        <div class="prose prose-lg max-w-none">
                            <div
                                class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6 border-l-4 border-blue-500">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $currentItem->title }}</h2>
                                @if($currentItem->description)
                                <p class="text-gray-600 text-lg leading-relaxed">{{ $currentItem->description }}</p>
                                @endif
                            </div>

                            @if($currentItem->text_content)
                            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                                <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed">
                                    {!! $currentItem->text_content !!}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endswitch

                    {{-- Completion Status and Action --}}
                    <div class="border-t border-gray-100 bg-gray-50 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                @if(in_array($currentItem->id, $completions))
                                <div class="flex items-center text-green-600">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium">Completed</span>
                                </div>
                                @else
                                <div class="flex items-center text-gray-500">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-medium">In Progress</span>
                                </div>
                                @endif
                            </div>

                            {{-- Auto-progress indicator --}}
                            <div class="flex items-center space-x-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span>Auto-tracking progress</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Footer - Fixed at Bottom --}}
            <div class="sticky bottom-0 border-t bg-white p-4 shadow-lg">
                <div class="container mx-auto px-4 flex justify-between items-center">
                    @if($previousNav)
                    <a href="{{ $previousNav['url'] }}"
                        onclick="trackNavigation(event, '{{ $previousNav['url'] }}', 'previous')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <div class="text-left">
                            <div class="text-xs text-gray-500">Previous</div>
                            <div class="truncate max-w-32">{{ $previousNav['title'] }}</div>
                        </div>
                    </a>
                    @else
                    <div></div>
                    @endif

                    <div class="flex items-center space-x-3">
                        @if($nextNav)
                        <a href="{{ $nextNav['url'] }}"
                            onclick="completeCurrentItemAndNavigate(event, '{{ $nextNav['url'] }}')"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                            <div class="text-right mr-2">
                                <div class="text-xs text-green-100">Next</div>
                                <div class="truncate max-w-32">{{ $nextNav['title'] }}</div>
                            </div>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        @else
                        {{-- Complete Course Button - Show when no next item --}}
                        @php
                        $totalItems = count($navigationSequence);
                        $completedItems = count($completions);
                        $totalQuizzes = $curriculumItems->whereNotNull('quiz')->count();
                        $completedQuizzes = $quizAttempts->where('status', 'completed')->count();
                        $allCompleted = ($completedItems + $completedQuizzes) >= ($totalItems);
                        @endphp

                        @if($allCompleted)
                        <a href="{{ route('courses.certificate.download', $course) }}"
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-left">
                                <div class="text-xs text-blue-100">Congratulations!</div>
                                <div>Complete Course</div>
                            </div>
                        </a>
                        @else
                        <button onclick="showCompletionReminder()"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-500 bg-gray-100 cursor-not-allowed">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-left">
                                <div class="text-xs text-gray-400">Complete all items</div>
                                <div>Finish Course</div>
                            </div>
                        </button>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="h-full flex items-center justify-center">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900">No content available</h3>
                <p class="mt-1 text-sm text-gray-500">This course doesn't have any content yet.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Responsive video container */
    .video-container {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%;
        /* 16:9 aspect ratio for desktop */
        min-height: 300px;
        /* Minimum height for very small screens */
    }

    .video-container>* {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 0.75rem 0.75rem 0 0;
        /* rounded-t-xl */
    }

    /* Tablet and larger screens - taller video */
    @media (min-width: 768px) {
        .video-container {
            padding-bottom: 60%;
            /* Slightly taller for better viewing */
            min-height: 400px;
        }
    }

    /* Desktop screens - optimal cinema experience */
    @media (min-width: 1024px) {
        .video-container {
            padding-bottom: 56.25%;
            /* Standard 16:9 */
            min-height: 550px;
        }
    }

    /* Large desktop screens - maintain good proportions */
    @media (min-width: 1280px) {
        .video-container {
            padding-bottom: 52%;
            /* Slightly wider for large screens */
            min-height: 650px;
        }
    }

    /* Extra large screens - cinema-like experience */
    @media (min-width: 1536px) {
        .video-container {
            padding-bottom: 50%;
            /* Cinema-like aspect ratio */
            min-height: 750px;
            max-height: 80vh;
            /* Don't exceed 80% of viewport height */
        }
    }

    /* Mobile portrait - more square-like for better mobile viewing */
    @media (max-width: 767px) and (orientation: portrait) {
        .video-container {
            padding-bottom: 75%;
            /* More square-like for mobile */
            min-height: 250px;
        }
    }

    /* Mobile landscape - use full width efficiently */
    @media (max-width: 767px) and (orientation: landscape) {
        .video-container {
            padding-bottom: 45%;
            /* Wider for landscape mobile */
            min-height: 200px;
        }
    }

    /* Ensure content doesn't go under the fixed navigation */
    .min-h-screen {
        padding-bottom: 70px;
        /* Height of the navigation bar */
    }

    /* Add padding to top of content to avoid overlap with mobile close button */
    @media (max-width: 1024px) {
        .flex-1 {
            padding-top: 4rem;
        }
    }

    /* Smooth transitions for responsive changes */
    .video-container {
        transition: all 0.3s ease;
    }

    /* Ensure video controls are always accessible */
    .video-container video {
        object-fit: contain;
    }

    /* Better iframe styling for YouTube */
    .video-container iframe {
        border: none;
        background: #000;
    }
</style>
@endpush

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    // Navigation-Based Progress Tracking System
let currentItemId = null;
let currentQuizId = null;

// Initialize navigation tracking
function initializeNavigationTracking() {
    // Get current item/quiz from URL
    const urlParams = new URLSearchParams(window.location.search);
    currentItemId = urlParams.get('item');
    currentQuizId = urlParams.get('quiz');

    // Track page view
    if (currentItemId || currentQuizId) {
        trackProgress('page_view');
    }
}

// Complete current item and navigate to next
function completeCurrentItemAndNavigate(event, nextUrl) {
    event.preventDefault();

    if (!currentItemId && !currentQuizId) {
        // No current item to complete, just navigate
        window.location.href = nextUrl;
        return;
    }

    // Show loading state
    const button = event.target.closest('a');
    const originalContent = button.innerHTML;
    button.innerHTML = `
        <div class="flex items-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Completing...</span>
        </div>
    `;

    // Complete current item
    trackProgress('complete_and_navigate')
        .then(() => {
            // Navigate to next item
            window.location.href = nextUrl;
        })
        .catch(error => {
            console.error('Error completing item:', error);
            // Restore button and navigate anyway
            button.innerHTML = originalContent;
            showNotification('Could not save progress, but continuing to next item.', 'warning');
            setTimeout(() => {
                window.location.href = nextUrl;
            }, 1000);
        });
}

// Track navigation (for Previous button)
function trackNavigation(event, url, direction) {
    // For previous navigation, just track the movement without completing
    trackProgress(`navigate_${direction}`);
    // Let the navigation proceed normally
}

// Track progress function
function trackProgress(action = 'view') {
    return new Promise((resolve, reject) => {
        if (!currentItemId && !currentQuizId) {
            resolve();
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/courses/{{ $course->id }}/track-progress`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                current_item: currentItemId,
                current_quiz: currentQuizId,
                action: action,
                time_spent: 0 // Not using time-based tracking
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateProgressUI(data.progress);

                // Show completion notification for Next navigation
                if (action === 'complete_and_navigate') {
                    showNotification('Item completed! Moving to next content.', 'success');
                }

                resolve(data);
            } else {
                reject(new Error(data.message || 'Failed to track progress'));
            }
        })
        .catch(error => {
            console.error('Progress tracking error:', error);
            reject(error);
        });
    });
}

// Update progress UI in real-time
function updateProgressUI(progress) {
    // Update progress bar
    const progressBar = document.getElementById('course-progress-bar');
    if (progressBar) {
        progressBar.style.width = `${progress.progress_percentage}%`;
    }

    // Update progress text
    const progressText = document.getElementById('course-progress-text');
    if (progressText) {
        progressText.textContent = `${progress.completed_progress} / ${progress.total_progress}`;
    }

    // Update sidebar completion indicators
    progress.completed_items.forEach(itemId => {
        updateSidebarCompletion(itemId);
    });

    // Update quiz completion indicators
    progress.completed_quizzes.forEach(quizId => {
        updateQuizCompletion(quizId);
    });

    // Update current item status
    if (currentItemId && progress.completed_items.includes(parseInt(currentItemId))) {
        updateCurrentItemStatus(true);
    }
}

// Update sidebar completion indicator
function updateSidebarCompletion(curriculumItemId) {
    const sidebarItem = document.querySelector(`a[href*="item=${curriculumItemId}"]`);
    if (sidebarItem) {
        // Update completion indicator
        const completionDiv = sidebarItem.querySelector('.w-6.h-6.bg-gray-200');
        if (completionDiv) {
            completionDiv.className = 'w-6 h-6 bg-green-500 rounded-full flex items-center justify-center';
            completionDiv.innerHTML = `
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            `;
        }

        // Update background color
        sidebarItem.classList.add('bg-green-50', 'border-green-200');
        sidebarItem.classList.remove('hover:bg-gray-50', 'border-transparent');
    }
}

// Update quiz completion indicator
function updateQuizCompletion(quizId) {
    const quizItem = document.querySelector(`a[href*="quiz/${quizId}"]`);
    if (quizItem) {
        const completionDiv = quizItem.querySelector('.w-6.h-6.bg-gray-200');
        if (completionDiv) {
            completionDiv.className = 'w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center';
            completionDiv.innerHTML = `
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            `;
        }

        quizItem.classList.add('bg-blue-50', 'border-blue-200');
        quizItem.classList.remove('hover:bg-gray-50', 'border-transparent');
    }
}

// Update current item status
function updateCurrentItemStatus(isCompleted) {
    const statusDiv = document.querySelector('.flex.items-center.text-gray-500');
    if (statusDiv && isCompleted) {
        statusDiv.className = 'flex items-center text-green-600';
        statusDiv.innerHTML = `
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">Completed</span>
        `;
    }
}

// Show completion reminder
function showCompletionReminder() {
    showNotification('Use the Next button to complete items and track your progress through the course.', 'info');
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;

    const bgColor = type === 'success' ? 'bg-green-500' :
                   type === 'error' ? 'bg-red-500' :
                   type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
    notification.classList.add(bgColor, 'text-white');

    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' ?
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                    type === 'error' ?
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>' :
                    type === 'warning' ?
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>' :
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Remove after 4 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeNavigationTracking();
});

</script>
@endpush
