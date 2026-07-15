<?php
$dir = __DIR__ . '/public/images/';
$files = scandir($dir);

foreach ($files as $file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $sourcePath = $dir . $file;
        $destPath = $dir . pathinfo($file, PATHINFO_FILENAME) . '.webp';
        
        $info = getimagesize($sourcePath);
        if ($info === false) continue;
        
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($sourcePath);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($sourcePath);
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        } else {
            continue;
        }

        // Resize if too large (Max 1920px width)
        $width = imagesx($image);
        $height = imagesy($image);
        $maxWidth = 1920;
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = floor($height * ($maxWidth / $width));
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $newImage;
        }

        // Convert to WebP with 80% quality
        imagewebp($image, $destPath, 80);
        imagedestroy($image);
        echo "Converted $file to WebP\n";
    }
}
echo "Done.";
?>
