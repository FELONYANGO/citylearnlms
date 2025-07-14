<footer class="bg-primary-dark text-white py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1">
                <div class="flex items-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10">
                    <span class="ml-2 text-xl font-semibold">Nairobi County</span>
                </div>
                <p class="text-gray-300 mb-4">Empowering Nairobi County through accessible, high-quality training
                    and education for all.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-linkedin"></i></a>
                    <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white">Home</a></li>
                    <li><a href="#programs" class="text-gray-300 hover:text-white">Programs</a></li>
                    <li><a href="#courses" class="text-gray-300 hover:text-white">Courses</a></li>
                    <li><a href="#pricing" class="text-gray-300 hover:text-white">Pricing</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white">Contact</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                <ul class="space-y-2">
                    <li class="flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> Nairobi City Hall,
                        Nairobi, Kenya</li>
                    <li class="flex items-center"><i class="fas fa-envelope mr-2"></i> info@nairobi-training.go.ke
                    </li>
                    <li class="flex items-center"><i class="fas fa-phone mr-2"></i> +254 700 000 000</li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Operating Hours</h3>
                <ul class="space-y-2">
                    <li class="flex justify-between">
                        <span>Monday - Friday:</span>
                        <span>8:00 AM - 5:00 PM</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Saturday:</span>
                        <span>9:00 AM - 1:00 PM</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Sunday:</span>
                        <span>Closed</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center">
            <p class="text-gray-300">&copy; {{ date('Y') }} Nairobi County Training Center. All rights reserved.</p>
        </div>
    </div>
</footer>
