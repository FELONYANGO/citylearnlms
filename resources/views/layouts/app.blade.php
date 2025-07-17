<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Nairobi County Training Center') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    <style>
        .mega-menu {
            display: none;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: max-content;
            min-width: 900px;
            background: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-radius: 0.5rem;
            z-index: 50;
            margin-top: 0.5rem;
            border: 1px solid #e5e7eb;
            padding: 1.5rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .nav-item:hover .mega-menu {
            display: block;
        }

        .mega-menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .menu-section {
            margin-bottom: 2rem;
        }

        .menu-section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #2B593F;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .menu-section-title i {
            font-size: 1.5rem;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .category-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 0.5rem;
            background: #f8fafc;
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb;
        }

        .category-item:hover {
            background: #f1f5f9;
            border-color: #2B593F;
            transform: translateY(-2px);
        }

        .category-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(43, 89, 63, 0.1);
            border-radius: 0.5rem;
            margin-right: 1rem;
            color: #2B593F;
            transition: all 0.2s ease;
        }

        .category-item:hover .category-icon {
            background: #2B593F;
            color: white;
        }

        .category-info {
            flex: 1;
        }

        .category-name {
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .category-description {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .menu-footer {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .view-all-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: rgba(43, 89, 63, 0.1);
            color: #2B593F;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .view-all-btn:hover {
            background: #2B593F;
            color: white;
        }

        .view-all-btn i {
            margin-left: 0.5rem;
            transition: transform 0.2s ease;
        }

        .view-all-btn:hover i {
            transform: translateX(3px);
        }

        .category-badge {
            background: #2B593F;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10">
                        <div class="ml-3">
                            <h1 class="text-xl font-semibold text-gray-900">Nairobi County</h1>
                            <p class="text-sm text-gray-600">Training Center</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Home</a>

                    <!-- Featured Programs Dropdown -->
                    <div class="relative nav-item">
                        <a href="#"
                            class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">
                            Featured Programs
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>

                        <!-- Mega Menu -->
                        <div class="mega-menu">
                            <!-- Course Library Section -->
                            <div class="menu-section">
                                <h2 class="menu-section-title">
                                    <i class="fas fa-book-open"></i>
                                    Course Library
                                </h2>
                                <div class="category-grid">
                                    @php
                                    $categoryIcons = [
                                    'Fire Safety' => 'fa-fire',
                                    'Food Hygiene' => 'fa-utensils',
                                    'Health & Safety' => 'fa-shield-alt',
                                    'First Aid' => 'fa-medkit',
                                    'Mental Health' => 'fa-brain',
                                    'Leadership' => 'fa-users',
                                    'Remote Working' => 'fa-laptop-house',
                                    'Cyber Security' => 'fa-shield-virus',
                                    'Workplace Culture' => 'fa-building-user',
                                    'Care Certificate' => 'fa-certificate',
                                    'Mandatory Training' => 'fa-clipboard-check',
                                    'Professional Development' => 'fa-graduation-cap'
                                    ];
                                    @endphp

                                    @foreach(App\Models\Category::all() as $category)
                                    <a href="#" class="category-item">
                                        <div class="category-icon">
                                            <i
                                                class="fas {{ $categoryIcons[$category->name] ?? 'fa-folder' }} fa-lg"></i>
                                        </div>
                                        <div class="category-info">
                                            <div class="category-name">{{ $category->name }}</div>
                                            @if($category->subcategories &&
                                            is_array(json_decode($category->subcategories, true)))
                                            <div class="category-description">
                                                {{ count(json_decode($category->subcategories, true)) }} Courses
                                            </div>
                                            @endif
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Menu Footer -->
                            <div class="menu-footer">
                                <a href="#" class="view-all-btn">
                                    View All Courses
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                                <span class="category-badge">
                                    {{ App\Models\Category::count() }} Categories Available
                                </span>
                            </div>
                        </div>
                    </div>

                    <a href="#"
                        class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Courses</a>
                    <a href="#"
                        class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Testimonials</a>
                    <a href="{{ route('pricing') }}"
                        class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Pricing</a>
                    <a href="{{ route('contact') }}"
                        class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-3">
                    @auth
                    <a href="{{ route('dashboard') }}"
                        class="text-gray-500 hover:text-gray-900 px-3 py-2 text-sm font-medium">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="bg-red-600 text-white hover:bg-red-700 px-4 py-2 rounded-md text-sm font-medium">Logout</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}"
                        class="text-gray-500 hover:text-gray-900 px-3 py-2 text-sm font-medium">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-green-700 text-white hover:bg-green-800 px-4 py-2 rounded-md text-sm font-medium">Register</a>
                    <a href="#"
                        class="bg-blue-700 text-white hover:bg-blue-800 px-4 py-2 rounded-md text-sm font-medium">Contact
                        Sales</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <x-footer />
</body>

</html>