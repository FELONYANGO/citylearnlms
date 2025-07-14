<?php

require_once 'vendor/autoload.php';

// Test YouTube URL conversion
function testYouTubeUrl($url) {
    $videoId = '';

    // Check if it's a YouTube URL and convert to embed format
    if (str_contains($url, 'youtube.com/watch') || str_contains($url, 'youtu.be/')) {
        // Extract video ID from different YouTube URL formats
        if (str_contains($url, 'youtube.com/watch')) {
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            $videoId = $params['v'] ?? '';
        } elseif (str_contains($url, 'youtu.be/')) {
            $videoId = basename(parse_url($url, PHP_URL_PATH));
        }

        if (!empty($videoId)) {
            $embedUrl = "https://www.youtube.com/embed/{$videoId}?rel=0&modestbranding=1&showinfo=0";
            echo "Original: {$url}\n";
            echo "Video ID: {$videoId}\n";
            echo "Embed URL: {$embedUrl}\n";
            echo "Is YouTube: Yes\n\n";
        }
    } else {
        echo "Original: {$url}\n";
        echo "Is YouTube: No\n\n";
    }
}

// Test different YouTube URL formats
testYouTubeUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
testYouTubeUrl('https://youtu.be/dQw4w9WgXcQ');
testYouTubeUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=30s');
testYouTubeUrl('https://example.com/video.mp4');
