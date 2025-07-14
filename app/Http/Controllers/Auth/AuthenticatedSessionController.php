<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $intended = redirect()->intended()->getTargetUrl();

        // If there's no intended URL or it's the home page, use role-based redirect
        if (!$intended || $intended === url(RouteServiceProvider::HOME)) {
            // Admin goes to Filament dashboard
            if ($user->hasRole('admin')) {
                return redirect()->route('filament.admin.pages.dashboard');
            }

            // Trainer goes to trainer dashboard
            if ($user->hasRole('trainer')) {
                return redirect()->route('trainer.dashboard');
            }

            // Student goes to student dashboard
            if ($user->hasRole('student')) {
                return redirect()->route('student.dashboard');
            }

            // Organization representative
            if ($user->hasRole('org_rep')) {
                return redirect()->route('organization.dashboard');
            }

            // Default fallback
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // If there's an intended URL, redirect there
        return redirect()->to($intended);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
