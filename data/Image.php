<?php

class Image{

    public function upload($posted_image, $target_file) {
        $uploadOk = 1;
        //$posted_image = $_FILES["image-1"]["tmp_name"]; // variable example

        try {
            // Check if image file is a actual image or fake image
            $check = getimagesize($posted_image);
            if ($check === false) {
                $message = $message . "File is not an image. <br>";
                $uploadOk = 0;
            }
        } catch (Exception $exc) {
            echo 'Fatal exception caught: '.$exc->getMessage();
            $uploadOk = 0;
        } catch (Error $err) {
            echo 'Fatal error caught: '.$err->getMessage();
            $uploadOk = 0;
        }
    
        try {
        // Check if everything is ok to upload
            if ($uploadOk == 1) {
                if (move_uploaded_file($posted_image, $target_file)) {
                    //$message = $message . "The file ". htmlspecialchars(basename($_FILES["image-1"]["name"])). " has been uploaded.";
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                    //$message = $message . "Sorry, there was an error uploading your file.";
                }
            }
        } catch (Exception $exc) {
            echo 'unable to upload EX : '.$exc->getMessage();
            $uploadOk = 0;
        } catch (Error $err) {
            echo 'unable to upload Err : '.$err->getMessage();
            $uploadOk = 0;
        }
        
        return $uploadOk;
    }  
    
    public function remove($baseName) {
        // Base name of the file without extension
        //$baseName = 'assets/img/prod/prod_img_1';

        // Use glob() to find files matching the base name with any extension
        $files = glob($baseName . '.*'); // This will match prod_img_1.jpg, prod_img_1.png, etc.

        // Check if any files were found
        if (!empty($files)) {
            foreach ($files as $file) {
                // Attempt to delete the file
                if (unlink($file)) {
                    echo "Successfully deleted: $file<br>";
                } else {
                    echo "Error deleting: $file<br>";
                }
            }
        } else {
            echo "No files found matching: $baseName.*<br>";
        }
    } 

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