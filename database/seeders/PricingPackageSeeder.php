<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PricingPackage;

class PricingPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic',
                'description' => 'Perfect for individuals starting their learning journey',
                'price' => 2999.00,
                'currency' => 'KES',
                'billing_period' => 'monthly',
                'color_theme' => 'blue',
                'features' => [
                    ['feature' => 'Access to 10 courses'],
                    ['feature' => 'Monthly live sessions'],
                    ['feature' => 'Email support'],
                    ['feature' => 'Basic certificates'],
                    ['feature' => 'Mobile app access'],
                ],
                'is_popular' => false,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 1,
                'button_text' => 'Get Started',
                'button_link' => null,
            ],
            [
                'name' => 'Professional',
                'description' => 'Ideal for professionals looking to advance their careers',
                'price' => 4999.00,
                'currency' => 'KES',
                'billing_period' => 'monthly',
                'color_theme' => 'green',
                'features' => [
                    ['feature' => 'Access to 50+ courses'],
                    ['feature' => 'Weekly live sessions'],
                    ['feature' => 'Priority support'],
                    ['feature' => 'Professional certificates'],
                    ['feature' => 'Downloadable resources'],
                    ['feature' => 'Career guidance'],
                    ['feature' => 'Skills assessment'],
                ],
                'is_popular' => true,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
                'button_text' => 'Start Pro',
                'button_link' => null,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Comprehensive solution for organizations and teams',
                'price' => 9999.00,
                'currency' => 'KES',
                'billing_period' => 'monthly',
                'color_theme' => 'purple',
                'features' => [
                    ['feature' => 'Unlimited course access'],
                    ['feature' => 'Daily live sessions'],
                    ['feature' => '24/7 priority support'],
                    ['feature' => 'Custom certificates'],
                    ['feature' => 'Team management'],
                    ['feature' => 'Analytics dashboard'],
                    ['feature' => 'API access'],
                    ['feature' => 'Custom integrations'],
                ],
                'is_popular' => false,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
                'button_text' => 'Contact Sales',
                'button_link' => '/contact',
            ],
            [
                'name' => 'Student',
                'description' => 'Special pricing for students and educational institutions',
                'price' => 1999.00,
                'currency' => 'KES',
                'billing_period' => 'monthly',
                'color_theme' => 'orange',
                'features' => [
                    ['feature' => 'Access to 20 courses'],
                    ['feature' => 'Student resources'],
                    ['feature' => 'Study groups'],
                    ['feature' => 'Academic certificates'],
                    ['feature' => 'Flexible scheduling'],
                ],
                'is_popular' => false,
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 0,
                'button_text' => 'Student Discount',
                'button_link' => null,
            ],
            [
                'name' => 'Lifetime',
                'description' => 'One-time payment for lifetime access to all content',
                'price' => 49999.00,
                'currency' => 'KES',
                'billing_period' => 'lifetime',
                'color_theme' => 'red',
                'features' => [
                    ['feature' => 'Lifetime access to all courses'],
                    ['feature' => 'All future courses included'],
                    ['feature' => 'Priority support'],
                    ['feature' => 'All certificates'],
                    ['feature' => 'Exclusive member events'],
                    ['feature' => 'No recurring fees'],
                ],
                'is_popular' => false,
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 4,
                'button_text' => 'Buy Lifetime',
                'button_link' => null,
            ],
        ];

        foreach ($packages as $package) {
            PricingPackage::create($package);
        }
    }
}
