<?php

// Create storage symlink manually
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

// Remove existing link if it exists
if (is_link($link)) {
    unlink($link);
}

// Create the symlink
if (symlink($target, $link)) {
    echo "Storage link created successfully!\n";
    echo "Target: $target\n";
    echo "Link: $link\n";
} else {
    echo "Failed to create storage link.\n";
    echo "You may need to run this as administrator on Windows.\n";
    echo "Alternatively, try: php artisan storage:link\n";
}
