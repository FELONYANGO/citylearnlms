<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address', 'logo', 'cover_image', 'more_info'];
    public function users() { return $this->hasMany(User::class); }

}
