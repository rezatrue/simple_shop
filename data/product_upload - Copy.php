<?php
// Include the Database class
require 'Database.php'; // Adjust the path as necessary
require 'Image_resize.php';

session_start(); // Start the session


$target_project = "/001_shop/";
$prod_img_folder = "assets/img/prod/";
$message = null; // if not null we will send it via session

   

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // collect all data form the post request
        if (isset($_POST['ProductName']) && $_POST['ProductName'] != null)
            $productName = $_POST['ProductName'];
    
        if (isset($_POST['ProductPrice']))
            $productPrice = $_POST['ProductPrice']; 
    
        if (isset($_POST['ProductSize']))
            $productSize = $_POST['ProductSize'];         
    
        if(isset($_POST['IsFeatured'])) 
            $isFeatured = 1;
        else
            $isFeatured = 0;

        if(isset($_POST['IsShow'])) 
            $isShowed = 1;
        else
            $isShowed = 0;
    
        if (isset($_POST['selected-category']))
        $selectedCategories = $_POST['selected-category']; 

        if (isset($_POST['ProductDescription']))
            $productDescription = $_POST['ProductDescription'];         
    
        if (isset($_POST['ProductSpecification']))
            $productSpecification = $_POST['ProductSpecification']; 
    
        if (isset($_POST['id']))
            $productId = $_POST['id']; // if not null then we have to update category (not add as new)

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($_FILES["CategoryImage"]["name"], PATHINFO_EXTENSION));

    // insert / add new category in the table(DB) without image & get image cat id
    // generate a image name for the cat image with id ex: prod_img_id
    // insert the image path in the table(DB)
    if($productId != null && $uploadOk == 1){
        // Edit/Update category item
        $db = new Database();
        if($imageFileType){
           $image_name = "prod_img_" .$productId . "." .  $imageFileType;
           $realtive_image_path = $prod_img_folder . $image_name ; 
        }else{
            $realtive_image_path = null;
        }
        
        $db->updateProduct($productName, $realtive_image_path, $productPrice, $productSize, $selectedCategories, 
                            $isShowed, $isFeatured, $productDescription, $productSpecification, $productId); 
        $db->close(); 
    }

    if($productId == null && $uploadOk == 1){
        $db = new Database();
        $prod_id = $db->addToProduct($productName, null, $productPrice, $productSize, $selectedCategories, 
                                       $isShowed, $isFeatured, $productDescription, $productSpecification);
        $image_name = "prod_img_" .$prod_id . "." .  $imageFileType;
        $realtive_image_path = $prod_img_folder . $image_name ;
        $db->updateProduct(null, $realtive_image_path, null, null, null, null, null, null, $prod_id);
        $db->close();  
    }

    // upload the image to 'assets/img/prod/' folder    
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . $target_project . $prod_img_folder;
    $target_file = $target_dir . $image_name;

    clearstatcache(); // Clear the cache

    // Check if the directory is writable
    if (is_dir($target_dir)) {
        if (!is_writable($target_dir)) {
            // Attempt to change the permissions to make it writable
            chmod($target_dir, 0755); // Set permissions to 0755
            echo "Directory permissions changed to writable.";
        }
    }

    // Check if uploads directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // Create the directory if it does not exist
    }

    try {
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["CategoryImage"]["tmp_name"]);
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
            if (move_uploaded_file($_FILES["CategoryImage"]["tmp_name"], $target_file)) {
                $message = $message . "The file ". htmlspecialchars(basename($_FILES["CategoryImage"]["name"])). " has been uploaded.";
            } else {
                $uploadOk = 0;
                $message = $message . "Sorry, there was an error uploading your file.";
            }
        }
    } catch (Exception $exc) {
        echo 'unable to upload EX : '.$exc->getMessage();
        $uploadOk = 0;
    } catch (Error $err) {
        echo 'unable to upload Err : '.$err->getMessage();
        $uploadOk = 0;
    }  
}    

// resize image resulation
    // if($uploadOk == 1){
    //     $img_resize = new Image_resize();
    //     $img_resize->resize(800, 800, $target_file);
    // }

$_SESSION['message'] = $message;

if($productId != null){
    // Redirect back to CategoryListPage
header("Location: ../product_list.php");

}
else{
// Redirect back to addCategory.php
header("Location: ../add_product.php");
}
exit(); // Ensure no further code is executed    
   




?>