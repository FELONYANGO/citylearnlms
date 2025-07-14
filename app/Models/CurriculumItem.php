<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurriculumItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'content_type',
        'file_url',
        'video_url',
        'text_content',
        'order',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    //media

    //write the method explanation in simple words
    /**
     * Get the media resource for the curriculum item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mediaResource()
    {
        return $this->belongsTo(MediaResource::class);
    }

    public function mediaResources(): HasMany
    {
        return $this->hasMany(MediaResource::class);
    }

    /**
     * Get the quiz associated with this curriculum item.
     * Each curriculum item can have one quiz.
     */
    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    /**
     * Get the proper file URL for display
     */
    public function getFileDisplayUrl()
    {
        if (!$this->file_url) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->file_url, FILTER_VALIDATE_URL)) {
            return $this->file_url;
        }

        // Otherwise, generate storage URL
        return \Storage::url($this->file_url);
    }

    /**
     * Get the proper video URL for display
     */
    public function getVideoDisplayUrl()
    {
        if (!$this->video_url) {
            return null;
        }

        // If it's already a full URL, return as is
        if (filter_var($this->video_url, FILTER_VALIDATE_URL)) {
            return $this->video_url;
        }

        // Otherwise, generate storage URL
        return \Storage::url($this->video_url);
    }

    /**
     * Convert YouTube URL to embed format
     */
    public function getYouTubeEmbedUrl()
    {
        if (!$this->video_url) {
            return null;
        }

        $videoUrl = $this->video_url;

        // Check if it's a YouTube URL
        if (str_contains($videoUrl, 'youtube.com/watch') || str_contains($videoUrl, 'youtu.be/')) {
            $videoId = '';

            // Extract video ID from different YouTube URL formats
            if (str_contains($videoUrl, 'youtube.com/watch')) {
                parse_str(parse_url($videoUrl, PHP_URL_QUERY), $params);
                $videoId = $params['v'] ?? '';
            } elseif (str_contains($videoUrl, 'youtu.be/')) {
                $videoId = basename(parse_url($videoUrl, PHP_URL_PATH));
            }

            if (!empty($videoId)) {
                return "https://www.youtube.com/embed/{$videoId}?rel=0&modestbranding=1&showinfo=0";
            }
        }

        return null;
    }

    /**
     * Check if video URL is a YouTube link
     */
    public function isYouTubeVideo()
    {
        if (!$this->video_url) {
            return false;
        }

        return str_contains($this->video_url, 'youtube.com/watch') || str_contains($this->video_url, 'youtu.be/');
    }

    /**
     * Get file extension from file_url
     */
    public function getFileExtension()
    {
        if (!$this->file_url) {
            return null;
        }

        return strtolower(pathinfo($this->file_url, PATHINFO_EXTENSION));
    }

    /**
     * Check if the file is a specific type
     */
    public function isFileType($types)
    {
        $extension = $this->getFileExtension();
        return $extension && in_array($extension, (array) $types);
    }

    /**
     * Get file type category
     */
    public function getFileTypeCategory()
    {
        $extension = $this->getFileExtension();

        if (!$extension) {
            return 'unknown';
        }

        $categories = [
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
            'video' => ['mp4', 'webm', 'ogg', 'avi', 'mov'],
            'audio' => ['mp3', 'wav', 'ogg', 'aac'],
            'pdf' => ['pdf'],
            'document' => ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
            'archive' => ['zip', 'rar', '7z', 'tar', 'gz'],
        ];

        foreach ($categories as $category => $extensions) {
            if (in_array($extension, $extensions)) {
                return $category;
            }
        }

        return 'file';
    }
}
