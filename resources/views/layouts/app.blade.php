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
                    <a href="#"
                        class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">Featured
                        Programs</a>
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