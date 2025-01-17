<?php
// Include the Database class
require 'Database.php'; // Adjust the path as necessary
require 'Image.php';

session_start(); // Start the session

$target_project = "/001_shop/";
$prod_img_folder = "assets/img/prod/";
$message = null; // if not null we will send it via session
$img = new Image();

// upload the image to 'assets/img/prod/' folder    
$target_dir = $_SERVER['DOCUMENT_ROOT'] . $target_project . $prod_img_folder;
console.info('' . $target_dir . '');
console.log(''. $target_dir . '');
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

        // insert / add new category in the table(DB) without image & get image cat id
        // generate a image name for the cat image with id ex: prod_img_id
        // insert the image path in the table(DB)
            $imageLocations = [];
            $db = new Database();
            if($productId){
                $prod_id = $productId;
                $imageLocations = $db->getProductDetails($prod_id)['p_images'];
            }else{
                $prod_id = $db->addToProduct($productName, null, $productPrice, $productSize, $selectedCategories, 
                                               $isShowed, $isFeatured, $productDescription, $productSpecification); 
            }
            $db->close();
            if(!$prod_id) return;

            $imgRemoveList = [];
            for ($x = 1; $x <= 9; $x++) {
                // Construct the input name dynamically
                $inputName = "image-" . $x;
                // Check if the file is uploaded and exists
                if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] == UPLOAD_ERR_OK) {
                    // Get the file extension
                    $imageFileType = strtolower(pathinfo($_FILES[$inputName]["name"], PATHINFO_EXTENSION));
                    $image_name = "prod_img_" .$prod_id . "-".$x."." .  $imageFileType;
                    $realtive_image_path = $prod_img_folder . $image_name ;
                    $target_file = $target_dir . $image_name;
                    $upStatus = $img->upload($_FILES[$inputName]["tmp_name"], $target_file); // $_FILES["image-1"]["tmp_name"]
                    if($upStatus)
                        $imageLocations[$x-1] = $realtive_image_path;
                    else{
                        if($imageLocations[$x-1] && str_contains($_POST['image_src-'.$x], 'assets/img/cat/dumy.png')){
                            $imgRemoveList[] = $imageLocations[$x-1];
                            $imageLocations[$x-1] = null;
                        }
                    }     
                } else {
                    if($imageLocations[$x-1] && str_contains($_POST['image_src-'.$x], 'assets/img/cat/dumy.png')){
                        $imgRemoveList[] = $imageLocations[$x-1];
                        $imageLocations[$x-1] = null;
                        //$imgRemoveList[] = $prod_img_folder . "prod_img_" . $prod_id . "-" . $x ;
                    }
                }
            }

            // Convert image paths array to JSON format
            $jsonImagePaths = json_encode($imageLocations);
            $message = $jsonImagePaths . " has been uploaded.";

            // Edit/Update category item
            $db = new Database();
            if($productId){
                $db->updateProduct($productName, $jsonImagePaths, $productPrice, $productSize, $selectedCategories, 
                                $isShowed, $isFeatured, $productDescription, $productSpecification, $prod_id); 
            }else{
                $db->updateProduct(null, $jsonImagePaths, null, null, null, null, null, null, null, $prod_id); // Images updated
            }
            $db->close();  

            if($productId){ // remove if edit product
                // remove image if not listed
                // echo '<pre>';
                // print_r($imgRemoveList);
                // echo '</pre>';
                // exit();
                foreach($imgRemoveList as $image){
                    unlink('../'.$image);
                }
            }    

}    

// resize image resulation
    // if($uploadOk == 1){
    //     $img = new Image();
    //     $img->resize(800, 800, $target_file);
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