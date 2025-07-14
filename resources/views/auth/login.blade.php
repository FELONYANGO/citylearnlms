<x-guest-layout>
    <div class="min-h-screen flex">
        <!-- Left Panel - Solid Green Background -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#2B593F] flex-col justify-start p-12">
            <div class="flex items-center gap-3 mb-16">
                <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4L3 9L12 14L21 9L12 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3 14L12 19L21 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <h2 class="text-white text-xl font-semibold leading-none">Nairobi County</h2>
                    <p class="text-white/80 text-sm">Training Center</p>
                </div>
            </div>

            <h1 class="text-white text-4xl font-bold mb-4">Welcome Back!</h1>
            <p class="text-white/90 text-lg leading-relaxed">
                Access your personalized learning dashboard and<br>
                continue your professional development journey.
            </p>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md">
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900">Log in to your account</h2>
                    <p class="mt-2 text-sm text-gray-600">Sign in to access your learning dashboard</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
                        <input id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#2B593F] focus:border-[#2B593F]"
                        >
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">PASSWORD</label>
                        <input id="password"
                            type="password"
                            name="password"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-[#2B593F] focus:border-[#2B593F]"
                        >
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-[#2B593F] to-blue-800 text-white rounded-md hover:from-[#234732] hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2B593F]">
                        LOGIN
                    </button>

                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-2 bg-white text-sm text-gray-500">or</span>
                        </div>
                    </div>

                    <!-- Forgot Password -->
                    <div class="text-center">
                        <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:text-[#2B593F]">
                            Forgot your password?
                        </a>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center text-sm text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-[#2B593F] hover:text-[#234732] font-medium">
                            Signup
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
