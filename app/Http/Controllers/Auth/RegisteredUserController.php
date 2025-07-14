<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Basic Information
            'name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],

            // Professional Information
            'professional_status' => ['nullable', 'in:professional,non_professional,student,unemployed,self_employed'],
            'industry' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'years_experience' => ['nullable', 'in:0-1,2-5,6-10,11-15,16+'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'organization_type' => ['nullable', 'in:company,ngo,government,educational_institution,self_employed,other'],
            'company_size' => ['nullable', 'in:1-10,11-50,51-200,201-1000,1000+'],
            'education_level' => ['nullable', 'in:high_school,diploma,bachelor,master,phd,other'],

            // Learning Preferences
            'learning_goals' => ['nullable', 'array'],
            'learning_goals.*' => ['string', 'in:career_advancement,skill_development,certification,personal_interest'],
            'preferred_learning_format' => ['nullable', 'in:self_paced,instructor_led,blended'],
            'time_availability' => ['nullable', 'array'],
            'time_availability.*' => ['string', 'in:weekdays,weekends,evenings'],
            'age_range' => ['nullable', 'in:18-25,26-35,36-45,46-55,56+'],
            'referral_source' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            // Basic Information
            'name' => $request->name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'country' => $request->country,
            'city' => $request->city,

            // Professional Information
            'professional_status' => $request->professional_status,
            'industry' => $request->industry,
            'job_title' => $request->job_title,
            'years_experience' => $request->years_experience,
            'organization_name' => $request->organization_name,
            'organization_type' => $request->organization_type,
            'company_size' => $request->company_size,
            'education_level' => $request->education_level,

            // Learning Preferences
            'learning_goals' => $request->learning_goals ? json_encode($request->learning_goals) : null,
            'preferred_learning_format' => $request->preferred_learning_format ?? 'self_paced',
            'time_availability' => $request->time_availability ? json_encode($request->time_availability) : null,
            'age_range' => $request->age_range,
            'referral_source' => $request->referral_source,
            'preferred_language' => 'en',

            // Profile completion tracking
            'profile_completed' => true,
            'profile_completed_at' => now(),

            // Default role assignment
            'role' => 'student',
        ]);

        event(new Registered($user));

        // Temporarily login user for email verification process
        Auth::login($user);

        return redirect()->route('verification.notice')->with('status', 'registration-success');
    }
}
