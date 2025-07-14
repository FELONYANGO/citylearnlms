<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MediaResource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'curriculum_item_id',
        'title',
        'resource_type',
        'file_url',
        'video_url',
        'link_url',
        'text_content',
    ];

    public function curriculumItem()
    {
        return $this->belongsTo(CurriculumItem::class);
    }

    //curriculum item

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::deleting(function ($mediaResource) {
    //         if ($mediaResource->file_url) {
    //             Storage::disk('public')->delete($mediaResource->file_url);
    //         }
    //     });
    // }
}
