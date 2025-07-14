<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'billing_period',
        'color_theme',
        'features',
        'is_popular',
        'is_featured',
        'is_active',
        'sort_order',
        'button_text',
        'button_link',
    ];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    public function getPriceWithCurrencyAttribute()
    {
        return $this->currency . ' ' . $this->formatted_price;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    public function getColorClasses()
    {
        $themes = [
            'blue' => [
                'bg' => 'bg-blue-500',
                'text' => 'text-blue-500',
                'border' => 'border-blue-500',
                'gradient' => 'from-blue-500 to-blue-600',
                'hover' => 'hover:bg-blue-600',
            ],
            'green' => [
                'bg' => 'bg-green-500',
                'text' => 'text-green-500',
                'border' => 'border-green-500',
                'gradient' => 'from-green-500 to-green-600',
                'hover' => 'hover:bg-green-600',
            ],
            'orange' => [
                'bg' => 'bg-orange-500',
                'text' => 'text-orange-500',
                'border' => 'border-orange-500',
                'gradient' => 'from-orange-500 to-orange-600',
                'hover' => 'hover:bg-orange-600',
            ],
            'purple' => [
                'bg' => 'bg-purple-500',
                'text' => 'text-purple-500',
                'border' => 'border-purple-500',
                'gradient' => 'from-purple-500 to-purple-600',
                'hover' => 'hover:bg-purple-600',
            ],
            'red' => [
                'bg' => 'bg-red-500',
                'text' => 'text-red-500',
                'border' => 'border-red-500',
                'gradient' => 'from-red-500 to-red-600',
                'hover' => 'hover:bg-red-600',
            ],
        ];

        return $themes[$this->color_theme] ?? $themes['blue'];
    }
}
