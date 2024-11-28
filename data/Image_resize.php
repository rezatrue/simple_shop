<?php

class Image_resize{

    public function resize($re_width, $re_height, $target_file) {
    // Resize image to 800x800 pixels
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    list($width, $height) = getimagesize($target_file);
    $new_width = $re_width;
    $new_height = $re_height;

    // Create a new true color image
    $thumb = imagecreatetruecolor($new_width, $new_height);

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Load the image based on its type
    switch ($imageFileType) {
        case 'jpg':
        case 'jpeg':
            $source = imagecreatefromjpeg($target_file);
            break;
        case 'png':
            $source = imagecreatefrompng($target_file);
            break;
        case 'gif':
            $source = imagecreatefromgif($target_file);
            break;
        default:
            echo "Unsupported image format.";
            exit;
    }

    // Resize the image
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    // Save the resized image back to the target directory
    switch ($imageFileType) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($thumb, $target_file);
            break;
        case 'png':
            imagepng($thumb, $target_file);
            break;
        case 'gif':
            imagegif($thumb, $target_file);
            break;
    }

    // Free up memory
    imagedestroy($thumb);
    imagedestroy($source);
    
    }

}
?>