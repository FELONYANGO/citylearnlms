<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PassCardTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'html_content',
        'configuration',
        'placeholders',
        'is_active',
    ];

    protected $casts = [
        'configuration' => 'array',
        'placeholders' => 'array',
        'is_active' => 'boolean',
    ];

    public function passCards(): HasMany
    {
        return $this->hasMany(PassCard::class, 'template_id');
    }
}
