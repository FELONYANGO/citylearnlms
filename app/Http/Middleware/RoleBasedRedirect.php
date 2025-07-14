<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleBasedRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admin goes to Filament
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

        // If no specific role or unknown role, redirect to home
        return redirect()->route('home');
    }
}
