<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'favicon',
        'hero_video',
        'hero_image',
        'about_image',
        'testimonial_avatars',
        'site_title',
        'hero_title',
        'hero_subtitle',
        'hero_cta_text',
        'about_title',
        'about_description',
    ];

    protected $attributes = [
        'site_title' => 'Nairobi County Training Center',
        'hero_title' => 'Empowering Communities Through Education',
        'hero_subtitle' => 'Join thousands of learners in Nairobi County advancing their careers through our comprehensive training programs.',
        'hero_cta_text' => 'Explore Programs',
        'about_title' => 'About Our Training Center',
        'about_description' => 'We provide quality training programs to empower the community.',
    ];

    protected $casts = [
        'testimonial_avatars' => 'array',
    ];

    /**
     * Get the first (or create) homepage settings record
     */
    public static function current()
    {
        return static::firstOrCreate([
            'id' => 1
        ], [
            'site_title' => 'Nairobi County Training Center',
            'hero_title' => 'Empowering Communities Through Education',
            'hero_subtitle' => 'Join thousands of learners in Nairobi County advancing their careers through our comprehensive training programs.',
            'hero_cta_text' => 'Explore Programs',
            'about_title' => 'About Our Training Center',
            'about_description' => 'We provide quality training programs to empower the community.',
        ]);
    }
}
