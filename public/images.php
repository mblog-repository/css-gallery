<?php

require __DIR__."/../vendor/autoload.php";

use Intervention\Image\ImageManager;

$files = glob(__DIR__.'/images/*.*');

// get only images
$images = array_filter($files, function ($file) {
    return in_array(
        pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']
    );
});

$result = [];

$manager = new ImageManager();

foreach ($images as $image) {
    $pathinfo = pathinfo($image);

    if (! file_exists(__DIR__."/images/thumbs/".$pathinfo['basename'])) {
        if (! is_dir(__DIR__."/images/thumbs/")) {
            mkdir(__DIR__."/images/thumbs/");
        }

        $manager->make($image)->resize(300, 300)->save(
            __DIR__."/images/thumbs/".$pathinfo['basename']
        );
    }

    $result[] = [
        'image' => '/images/'.$pathinfo['basename'],
        'thumbnail' => '/images/thumbs/'.$pathinfo['basename'],
    ];
}

header('Content-Type: application/json');

echo json_encode($result);
