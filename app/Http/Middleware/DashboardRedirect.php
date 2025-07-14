<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardRedirect
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            // Check if user is admin
            if ($request->user()->hasRole('admin')) {
                return redirect()->route('filament.admin.pages.dashboard');
            }

            // Check if user is trainer
            if ($request->user()->hasRole('trainer')) {
                return redirect()->route('trainer.dashboard');
            }

            // Default student dashboard
            return redirect()->route('student.dashboard');
        }

        return $next($request);
    }
}
