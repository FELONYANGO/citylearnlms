@extends('layouts.app')

@section('content')
<div class="min-h-screen flex">
    <!-- Left Panel - Image and Branding -->
    <div
        class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#2B593F] to-[#1e3d2b] flex-col justify-center p-12 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent transform -skew-y-12">
            </div>
        </div>

        <div class="relative z-10">
            <!-- Logo and Brand -->
            <div class="flex items-center gap-3 mb-12">
                <svg class="w-10 h-10 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 4L3 9L12 14L21 9L12 4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M3 14L12 19L21 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <div>
                    <h2 class="text-white text-2xl font-bold leading-none">Nairobi County</h2>
                    <p class="text-white/90 text-sm">Training Center</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="space-y-6">
                <h1 class="text-white text-5xl font-bold leading-tight">
                    Join Our<br>
                    <span class="text-green-200">Learning</span><br>
                    Community
                </h1>

                <p class="text-white/90 text-lg leading-relaxed max-w-md">
                    Start your professional development journey with expert-led courses and personalized learning paths.
                </p>

                <!-- Features -->
                <div class="space-y-4 mt-8">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-white/90">Expert-led courses</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-white/90">Professional certification</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-white/90">Flexible learning schedule</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Panel - Registration Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-lg">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Create Your Account</h2>
                <p class="mt-2 text-gray-600">Join thousands of learners advancing their careers</p>
            </div>

            <!-- Registration Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form method="POST" action="{{ route('register') }}" id="registration-form">
                    @csrf

                    <!-- Progress Steps -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-[#2B593F] text-white rounded-full flex items-center justify-center text-sm font-medium step-indicator active"
                                    data-step="1">1</div>
                                <span class="text-sm font-medium text-gray-900">Basic</span>
                            </div>
                            <div class="flex-1 h-0.5 bg-gray-200 mx-4">
                                <div class="h-full bg-[#2B593F] transition-all duration-300 progress-bar"
                                    style="width: 33%"></div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium step-indicator"
                                    data-step="2">2</div>
                                <span class="text-sm font-medium text-gray-600">Professional</span>
                            </div>
                            <div class="flex-1 h-0.5 bg-gray-200 mx-4"></div>
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium step-indicator"
                                    data-step="3">3</div>
                                <span class="text-sm font-medium text-gray-600">Preferences</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Basic Information -->
                    <div class="form-step" data-step="1" style="display: block;">
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- First Name -->
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First
                                        Name</label>
                                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                    @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last
                                        Name</label>
                                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                    @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Hidden full name field for backward compatibility -->
                            <input type="hidden" name="name" id="full_name" value="{{ old('name') }}">

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                    Address</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone
                                    Number</label>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Country -->
                                <div>
                                    <label for="country"
                                        class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                    <select id="country" name="country"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                        <option value="">Select Country</option>
                                        <option value="Kenya" {{ old('country')=='Kenya' ? 'selected' : '' }}>Kenya
                                        </option>
                                        <option value="Uganda" {{ old('country')=='Uganda' ? 'selected' : '' }}>Uganda
                                        </option>
                                        <option value="Tanzania" {{ old('country')=='Tanzania' ? 'selected' : '' }}>
                                            Tanzania</option>
                                        <option value="Rwanda" {{ old('country')=='Rwanda' ? 'selected' : '' }}>Rwanda
                                        </option>
                                        <option value="Other" {{ old('country')=='Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                    @error('country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- City -->
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input id="city" name="city" type="text" value="{{ old('city') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                    @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Password -->
                                <div>
                                    <label for="password"
                                        class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <input id="password" name="password" type="password" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                    @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                    @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Professional Information -->
                    <div class="form-step" data-step="2" style="display: none;">
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Professional Status -->
                                <div>
                                    <label for="professional_status"
                                        class="block text-sm font-medium text-gray-700 mb-1">Professional Status</label>
                                    <select id="professional_status" name="professional_status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                        <option value="">Select Status</option>
                                        <option value="professional" {{ old('professional_status')=='professional'
                                            ? 'selected' : '' }}>Professional</option>
                                        <option value="non_professional" {{
                                            old('professional_status')=='non_professional' ? 'selected' : '' }}>
                                            Non-Professional</option>
                                        <option value="student" {{ old('professional_status')=='student' ? 'selected'
                                            : '' }}>Student</option>
                                        <option value="unemployed" {{ old('professional_status')=='unemployed'
                                            ? 'selected' : '' }}>Unemployed</option>
                                        <option value="self_employed" {{ old('professional_status')=='self_employed'
                                            ? 'selected' : '' }}>Self-Employed</option>
                                    </select>
                                    @error('professional_status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Industry -->
                                <div>
                                    <label for="industry"
                                        class="block text-sm font-medium text-gray-700 mb-1">Industry/Field</label>
                                    <input id="industry" name="industry" type="text" value="{{ old('industry') }}"
                                        placeholder="e.g., Technology, Healthcare"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                    @error('industry')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Job Title -->
                                <div>
                                    <label for="job_title" class="block text-sm font-medium text-gray-700 mb-1">Job
                                        Title</label>
                                    <input id="job_title" name="job_title" type="text" value="{{ old('job_title') }}"
                                        placeholder="e.g., Software Developer"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                    @error('job_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Years of Experience -->
                                <div>
                                    <label for="years_experience"
                                        class="block text-sm font-medium text-gray-700 mb-1">Experience</label>
                                    <select id="years_experience" name="years_experience"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                        <option value="">Select Experience</option>
                                        <option value="0-1" {{ old('years_experience')=='0-1' ? 'selected' : '' }}>0-1
                                            years</option>
                                        <option value="2-5" {{ old('years_experience')=='2-5' ? 'selected' : '' }}>2-5
                                            years</option>
                                        <option value="6-10" {{ old('years_experience')=='6-10' ? 'selected' : '' }}>
                                            6-10 years</option>
                                        <option value="11-15" {{ old('years_experience')=='11-15' ? 'selected' : '' }}>
                                            11-15 years</option>
                                        <option value="16+" {{ old('years_experience')=='16+' ? 'selected' : '' }}>16+
                                            years</option>
                                    </select>
                                    @error('years_experience')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Organization Name -->
                            <div>
                                <label for="organization_name"
                                    class="block text-sm font-medium text-gray-700 mb-1">Organization Name</label>
                                <input id="organization_name" name="organization_name" type="text"
                                    value="{{ old('organization_name') }}" placeholder="Company/Institution name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                @error('organization_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Organization Type -->
                                <div>
                                    <label for="organization_type"
                                        class="block text-sm font-medium text-gray-700 mb-1">Organization Type</label>
                                    <select id="organization_type" name="organization_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                        <option value="">Select Type</option>
                                        <option value="company" {{ old('organization_type')=='company' ? 'selected' : ''
                                            }}>Company</option>
                                        <option value="ngo" {{ old('organization_type')=='ngo' ? 'selected' : '' }}>NGO
                                        </option>
                                        <option value="government" {{ old('organization_type')=='government'
                                            ? 'selected' : '' }}>Government</option>
                                        <option value="educational_institution" {{
                                            old('organization_type')=='educational_institution' ? 'selected' : '' }}>
                                            Educational Institution</option>
                                        <option value="self_employed" {{ old('organization_type')=='self_employed'
                                            ? 'selected' : '' }}>Self-Employed</option>
                                        <option value="other" {{ old('organization_type')=='other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                    @error('organization_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Education Level -->
                                <div>
                                    <label for="education_level"
                                        class="block text-sm font-medium text-gray-700 mb-1">Education Level</label>
                                    <select id="education_level" name="education_level"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                        <option value="">Select Level</option>
                                        <option value="high_school" {{ old('education_level')=='high_school'
                                            ? 'selected' : '' }}>High School</option>
                                        <option value="diploma" {{ old('education_level')=='diploma' ? 'selected' : ''
                                            }}>Diploma</option>
                                        <option value="bachelor" {{ old('education_level')=='bachelor' ? 'selected' : ''
                                            }}>Bachelor's Degree</option>
                                        <option value="master" {{ old('education_level')=='master' ? 'selected' : '' }}>
                                            Master's Degree</option>
                                        <option value="phd" {{ old('education_level')=='phd' ? 'selected' : '' }}>PhD
                                        </option>
                                        <option value="other" {{ old('education_level')=='other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                    @error('education_level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Learning Preferences -->
                    <div class="form-step" data-step="3" style="display: none;">
                        <div class="space-y-6">
                            <!-- Learning Goals -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Learning Goals (Select all
                                    that apply)</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label
                                        class="flex items-center space-x-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="learning_goals[]" value="career_advancement"
                                            class="rounded border-gray-300 text-[#2B593F] focus:ring-[#2B593F]" {{
                                            in_array('career_advancement', old('learning_goals', [])) ? 'checked' : ''
                                            }}>
                                        <span class="text-sm text-gray-700">Career Advancement</span>
                                    </label>
                                    <label
                                        class="flex items-center space-x-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="learning_goals[]" value="skill_development"
                                            class="rounded border-gray-300 text-[#2B593F] focus:ring-[#2B593F]" {{
                                            in_array('skill_development', old('learning_goals', [])) ? 'checked' : ''
                                            }}>
                                        <span class="text-sm text-gray-700">Skill Development</span>
                                    </label>
                                    <label
                                        class="flex items-center space-x-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="learning_goals[]" value="certification"
                                            class="rounded border-gray-300 text-[#2B593F] focus:ring-[#2B593F]" {{
                                            in_array('certification', old('learning_goals', [])) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700">Certification</span>
                                    </label>
                                    <label
                                        class="flex items-center space-x-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="learning_goals[]" value="personal_interest"
                                            class="rounded border-gray-300 text-[#2B593F] focus:ring-[#2B593F]" {{
                                            in_array('personal_interest', old('learning_goals', [])) ? 'checked' : ''
                                            }}>
                                        <span class="text-sm text-gray-700">Personal Interest</span>
                                    </label>
                                </div>
                                @error('learning_goals')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Learning Format -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Preferred Learning
                                    Format</label>
                                <div class="space-y-2">
                                    <label
                                        class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="preferred_learning_format" value="self_paced"
                                            class="text-[#2B593F] focus:ring-[#2B593F]" {{
                                            old('preferred_learning_format', 'self_paced' )=='self_paced' ? 'checked'
                                            : '' }}>
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">Self-Paced</div>
                                            <div class="text-xs text-gray-500">Learn at your own pace</div>
                                        </div>
                                    </label>
                                    <label
                                        class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="preferred_learning_format" value="instructor_led"
                                            class="text-[#2B593F] focus:ring-[#2B593F]" {{
                                            old('preferred_learning_format')=='instructor_led' ? 'checked' : '' }}>
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">Instructor-Led</div>
                                            <div class="text-xs text-gray-500">Live sessions with instructors</div>
                                        </div>
                                    </label>
                                    <label
                                        class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="radio" name="preferred_learning_format" value="blended"
                                            class="text-[#2B593F] focus:ring-[#2B593F]" {{
                                            old('preferred_learning_format')=='blended' ? 'checked' : '' }}>
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">Blended Learning</div>
                                            <div class="text-xs text-gray-500">Mix of both approaches</div>
                                        </div>
                                    </label>
                                </div>
                                @error('preferred_learning_format')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Additional Info -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Age Range -->
                                <div>
                                    <label for="age_range" class="block text-sm font-medium text-gray-700 mb-1">Age
                                        Range (Optional)</label>
                                    <select id="age_range" name="age_range"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                        <option value="">Select Age Range</option>
                                        <option value="18-25" {{ old('age_range')=='18-25' ? 'selected' : '' }}>18-25
                                        </option>
                                        <option value="26-35" {{ old('age_range')=='26-35' ? 'selected' : '' }}>26-35
                                        </option>
                                        <option value="36-45" {{ old('age_range')=='36-45' ? 'selected' : '' }}>36-45
                                        </option>
                                        <option value="46-55" {{ old('age_range')=='46-55' ? 'selected' : '' }}>46-55
                                        </option>
                                        <option value="56+" {{ old('age_range')=='56+' ? 'selected' : '' }}>56+</option>
                                    </select>
                                    @error('age_range')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Referral Source -->
                                <div>
                                    <label for="referral_source"
                                        class="block text-sm font-medium text-gray-700 mb-1">How did you hear about
                                        us?</label>
                                    <select id="referral_source" name="referral_source"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B593F] focus:border-[#2B593F]">
                                        <option value="">Select Source</option>
                                        <option value="search_engine" {{ old('referral_source')=='search_engine'
                                            ? 'selected' : '' }}>Search Engine</option>
                                        <option value="social_media" {{ old('referral_source')=='social_media'
                                            ? 'selected' : '' }}>Social Media</option>
                                        <option value="friend_referral" {{ old('referral_source')=='friend_referral'
                                            ? 'selected' : '' }}>Friend/Colleague</option>
                                        <option value="advertisement" {{ old('referral_source')=='advertisement'
                                            ? 'selected' : '' }}>Advertisement</option>
                                        <option value="other" {{ old('referral_source')=='other' ? 'selected' : '' }}>
                                            Other</option>
                                    </select>
                                    @error('referral_source')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        <button type="button" id="prev-btn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2B593F] disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            Previous
                        </button>

                        <div class="flex space-x-3">
                            <button type="button" id="next-btn"
                                class="px-6 py-2 text-sm font-medium text-white bg-[#2B593F] border border-transparent rounded-md hover:bg-[#234732] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2B593F]">
                                Next Step
                            </button>

                            <button type="submit" id="submit-btn"
                                class="px-6 py-2 text-sm font-medium text-white bg-gradient-to-r from-[#2B593F] to-blue-800 border border-transparent rounded-md hover:from-[#234732] hover:to-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2B593F] hidden">
                                Create Account
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#2B593F] hover:text-[#234732] font-medium">
                        Sign in here
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Multi-step Form -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    console.log('Registration form script loaded');

    const steps = document.querySelectorAll('.form-step');
    const stepIndicators = document.querySelectorAll('.step-indicator');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    const form = document.getElementById('registration-form');
    const progressBar = document.querySelector('.progress-bar');

    let currentStep = 1;
    const totalSteps = steps.length;

    console.log('Found', steps.length, 'form steps');

    // Update full name when first/last name changes
    function updateFullName() {
        const firstName = document.getElementById('first_name').value;
        const lastName = document.getElementById('last_name').value;
        document.getElementById('full_name').value = `${firstName} ${lastName}`.trim();
    }

    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');

    if (firstNameInput && lastNameInput) {
        firstNameInput.addEventListener('input', updateFullName);
        lastNameInput.addEventListener('input', updateFullName);
    }

    function showStep(step) {
        console.log('Showing step:', step);

        // Hide all steps
        steps.forEach(s => {
            s.style.display = 'none';
            s.classList.remove('active');
        });

        // Reset step indicators
        stepIndicators.forEach(s => {
            s.classList.remove('active');
            s.classList.add('bg-gray-200', 'text-gray-600');
            s.classList.remove('bg-[#2B593F]', 'text-white');
        });

        // Show current step
        const currentStepElement = document.querySelector(`[data-step="${step}"].form-step`);
        if (currentStepElement) {
            currentStepElement.style.display = 'block';
            currentStepElement.classList.add('active');
        }

        // Update step indicators
        for (let i = 1; i <= step; i++) {
            const indicator = document.querySelector(`[data-step="${i}"].step-indicator`);
            if (indicator) {
                indicator.classList.add('bg-[#2B593F]', 'text-white');
                indicator.classList.remove('bg-gray-200', 'text-gray-600');
            }
        }

        // Update progress bar
        const progressPercentage = (step / totalSteps) * 100;
        if (progressBar) {
            progressBar.style.width = `${progressPercentage}%`;
        }

        // Update navigation buttons
        if (prevBtn) prevBtn.disabled = step === 1;

        if (step === totalSteps) {
            if (nextBtn) nextBtn.style.display = 'none';
            if (submitBtn) submitBtn.style.display = 'inline-flex';
        } else {
            if (nextBtn) nextBtn.style.display = 'inline-flex';
            if (submitBtn) submitBtn.style.display = 'none';
        }
    }

    function validateStep(step) {
        const currentStepElement = document.querySelector(`[data-step="${step}"].form-step`);
        if (!currentStepElement) return true;

        const requiredFields = currentStepElement.querySelectorAll('input[required], select[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            } else {
                field.classList.remove('border-red-500');
            }
        });

        // Special validation for step 1 (password confirmation)
        if (step === 1) {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');

            if (password && confirmPassword) {
                if (password.value !== confirmPassword.value) {
                    isValid = false;
                    confirmPassword.classList.add('border-red-500');
                } else {
                    confirmPassword.classList.remove('border-red-500');
                }
            }
        }

        return isValid;
    }

    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            console.log('Next button clicked, current step:', currentStep);
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            } else {
                alert('Please fill in all required fields before proceeding.');
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            console.log('Previous button clicked, current step:', currentStep);
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    }

    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');
            // Update full name before submission
            updateFullName();

            // Validate all required steps before submission
            if (!validateStep(1)) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                showStep(1);
                return;
            }
        });
    }

    // Initialize form - show first step
    console.log('Initializing form with step 1');
    showStep(1);
});
</script>
@endsection
