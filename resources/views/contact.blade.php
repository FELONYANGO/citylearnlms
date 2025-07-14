@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[400px] bg-gradient-to-br from-[#2B593F] via-[#1E4230] to-[#0F2419] overflow-hidden">
    <!-- Background overlay -->
    <div class="absolute inset-0 bg-black/20"></div>

    <!-- Animated background particles -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white opacity-5 rounded-full animate-pulse"></div>
        <div class="absolute top-3/4 right-1/4 w-32 h-32 bg-orange-300 opacity-10 rounded-full animate-bounce"></div>
        <div class="absolute top-1/2 left-3/4 w-48 h-48 bg-green-300 opacity-5 rounded-full animate-pulse delay-1000">
        </div>
    </div>

    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 min-h-[400px] flex items-center">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h1 class="text-5xl sm:text-6xl font-bold mb-6 animate-fade-in-up">
                Contact Us
            </h1>
            <p class="text-xl text-gray-100 leading-relaxed animate-fade-in-up animation-delay-300">
                Get in touch with our team for support, inquiries, or to learn more about our training programs.
            </p>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 transform hover:scale-105 transition-all duration-300">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a Message</h2>

                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First
                                Name</label>
                            <input type="text" id="first_name" name="first_name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#2B593F] focus:border-transparent transition-all duration-200"
                                required>
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last
                                Name</label>
                            <input type="text" id="last_name" name="last_name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#2B593F] focus:border-transparent transition-all duration-200"
                                required>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#2B593F] focus:border-transparent transition-all duration-200"
                            required>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#2B593F] focus:border-transparent transition-all duration-200">
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select id="subject" name="subject"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#2B593F] focus:border-transparent transition-all duration-200">
                            <option value="">Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="programs">Training Programs</option>
                            <option value="enrollment">Enrollment Questions</option>
                            <option value="technical">Technical Support</option>
                            <option value="partnership">Partnership Opportunities</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea id="message" name="message" rows="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#2B593F] focus:border-transparent transition-all duration-200"
                            placeholder="Tell us how we can help you..." required></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#2B593F] to-[#1E4230] text-white py-4 px-6 rounded-xl font-semibold hover:from-[#1E4230] hover:to-[#0F2419] transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="space-y-8">
                <!-- Contact Details -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Get in Touch</h2>

                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="bg-[#2B593F] text-white p-3 rounded-xl">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Address</h3>
                                <p class="text-gray-600">Nairobi County Training Center<br>
                                    City Hall Way, Nairobi<br>
                                    P.O. Box 30075-00100, Nairobi, Kenya</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-[#2B593F] text-white p-3 rounded-xl">
                                <i class="fas fa-phone text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Phone</h3>
                                <p class="text-gray-600">+254 20 2222000<br>
                                    +254 711 000 000</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-[#2B593F] text-white p-3 rounded-xl">
                                <i class="fas fa-envelope text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Email</h3>
                                <p class="text-gray-600">info@nairobicounty.go.ke<br>
                                    training@nairobicounty.go.ke</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div class="bg-[#2B593F] text-white p-3 rounded-xl">
                                <i class="fas fa-clock text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Office Hours</h3>
                                <p class="text-gray-600">Monday - Friday: 8:00 AM - 5:00 PM<br>
                                    Saturday: 9:00 AM - 1:00 PM<br>
                                    Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Quick Links</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('home') }}"
                            class="flex items-center space-x-3 text-gray-600 hover:text-[#2B593F] transition-colors">
                            <i class="fas fa-home text-sm"></i>
                            <span>Home</span>
                        </a>
                        <a href="{{ route('login') }}"
                            class="flex items-center space-x-3 text-gray-600 hover:text-[#2B593F] transition-colors">
                            <i class="fas fa-sign-in-alt text-sm"></i>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}"
                            class="flex items-center space-x-3 text-gray-600 hover:text-[#2B593F] transition-colors">
                            <i class="fas fa-user-plus text-sm"></i>
                            <span>Register</span>
                        </a>
                        <a href="#"
                            class="flex items-center space-x-3 text-gray-600 hover:text-[#2B593F] transition-colors">
                            <i class="fas fa-graduation-cap text-sm"></i>
                            <span>Courses</span>
                        </a>
                        <a href="#"
                            class="flex items-center space-x-3 text-gray-600 hover:text-[#2B593F] transition-colors">
                            <i class="fas fa-certificate text-sm"></i>
                            <span>Programs</span>
                        </a>
                        <a href="#"
                            class="flex items-center space-x-3 text-gray-600 hover:text-[#2B593F] transition-colors">
                            <i class="fas fa-info-circle text-sm"></i>
                            <span>About</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Site Map Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Site Map</h2>
            <p class="text-xl text-gray-600">Navigate through our website easily</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Main Pages -->
            <div class="bg-gray-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-[#2B593F] mb-4 flex items-center">
                    <i class="fas fa-home mr-2"></i>
                    Main Pages
                </h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}"
                            class="text-gray-600 hover:text-[#2B593F] transition-colors">Home</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="text-gray-600 hover:text-[#2B593F] transition-colors">Contact</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">About Us</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">FAQ</a></li>
                </ul>
            </div>

            <!-- Learning -->
            <div class="bg-gray-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-[#2B593F] mb-4 flex items-center">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Learning
                </h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">All Courses</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">Training Programs</a>
                    </li>
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">Categories</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">Certificates</a></li>
                </ul>
            </div>

            <!-- Account -->
            <div class="bg-gray-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-[#2B593F] mb-4 flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    Account
                </h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('login') }}"
                            class="text-gray-600 hover:text-[#2B593F] transition-colors">Login</a></li>
                    <li><a href="{{ route('register') }}"
                            class="text-gray-600 hover:text-[#2B593F] transition-colors">Register</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">Dashboard</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">Profile</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="bg-gray-50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <h3 class="text-xl font-bold text-[#2B593F] mb-4 flex items-center">
                    <i class="fas fa-life-ring mr-2"></i>
                    Support
                </h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">Help Center</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">Documentation</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-[#2B593F] transition-colors">Terms of Service</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Find Us</h2>
            <p class="text-xl text-gray-600">Visit our training center in Nairobi</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="aspect-video bg-gray-200 flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-map-marker-alt text-4xl text-[#2B593F] mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Location Map</h3>
                    <p class="text-gray-600">Interactive map will be integrated here</p>
                    <p class="text-sm text-gray-500 mt-2">Nairobi County Training Center<br>City Hall Way, Nairobi</p>
                </div>
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
</style>
@endsection