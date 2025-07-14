<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\TrainingProgram;
use App\Models\HomepageSettings;
use App\Models\PricingPackage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $homepageSettings = HomepageSettings::current();

        // Fetch training programs
        $trainingPrograms = TrainingProgram::with('category')
            ->where('status', 'published')
            ->latest()
            ->take(4)
            ->get();

        // Fetch latest courses with their categories
        $featuredCourses = Course::with('category')
            ->where('status', 'published')
            ->latest()
            ->take(4)
            ->get();

        // Fetch stats
        $stats = [
            'students_trained' => '5000+',
            'programs' => '50+'
        ];

        // Get pricing packages for homepage pricing section
        $pricingPackages = PricingPackage::active()
            ->orderBy('sort_order')
            ->get();

        return view('home', compact(
            'homepageSettings',
            'trainingPrograms',
            'featuredCourses',
            'stats',
            'pricingPackages'
        ))->with('siteSettings', $homepageSettings);
    }

    public function contact()
    {
        $homepageSettings = HomepageSettings::current();

        return view('contact', compact('homepageSettings'));
    }

    public function pricing()
    {
        $homepageSettings = HomepageSettings::current();

        // Get all active pricing packages ordered by sort_order
        $pricingPackages = PricingPackage::active()
            ->ordered()
            ->get();

        return view('pricing', compact('homepageSettings', 'pricingPackages'));
    }
}
