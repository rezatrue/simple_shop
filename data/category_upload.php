<?php
// Include the Database class
require 'Database.php'; // Adjust the path as necessary
require 'Image.php';

session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $message = null; // if not null we will send it via session

// collect all data form the post request
    if (isset($_POST['CategoryName']) && $_POST['CategoryName'] != null)
        $categoryName = strtolower($_POST['CategoryName']);

    if(isset($_POST['CategoryOfMonth'])) 
        $categoryOfMonth = 1;
    else
        $categoryOfMonth = 0;

    if (isset($_POST['SubCategoryName']))
        $subcategory = strtolower($_POST['SubCategoryName']); 

    if (isset($_POST['id']))
        $subcategoryId = $_POST['id']; // if not null then we have to update category (not add as new)

    $target_project = "/001_shop/";
    $cat_img_folder = "assets/img/cat/";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["CategoryImage"]["name"], PATHINFO_EXTENSION));
    

// insert / add new category in the table(DB) without image & get image cat id
// generate a image name for the cat image with id ex: cat_img_id
// insert the image path in the table(DB)
    if($subcategoryId != null && $uploadOk == 1){
        // Edit/Update category item
        $db = new Database();

        $categoryId = $db->catIdForSubCat($subcategoryId);

        if($_FILES["CategoryImage"]["name"] != null){
            $image_name = "cat_img_" .$categoryId . "." .  $imageFileType;
            $realtive_image_path = $cat_img_folder . $image_name ;
        }else{
            $realtive_image_path = null;
        }

        if($realtive_image_path == null){
            echo "name : " . $categoryName;
            echo " id : " . $categoryId;
            echo " sub name : " . $subcategory;
            echo " sub id : " . $subcategoryId;
            echo " nac : " . $categoryOfMonth;
            $db->updateCategory($categoryName, $categoryId, $subcategory, $subcategoryId, null, $categoryOfMonth);
        }
        else
            $db->updateCategory($categoryName, $categoryId, $subcategory, $subcategoryId, $realtive_image_path, $categoryOfMonth);
        $db->close(); 
    }
    
    if($subcategoryId == null && $uploadOk == 1){
        $db = new Database();

        // is cat exist
        $is_cat_id = $db->getCategoryIdFromName($categoryName);
       
        // if name exist (eather updating or adding/edating sub cat)
        if ($is_cat_id > 0) {
            $db->updateCategory(null, $is_cat_id, $subcategory, null, null, null);
        }
        else if ($is_cat_id == 0) {
            $cat_id = $db->addToCategory($categoryName, $subcategory, null, $categoryOfMonth);
            // check $cat_id number or not
            if($cat_id > 0){
                $image_name = "cat_img_" .$cat_id . "." .  $imageFileType;
                $realtive_image_path = $cat_img_folder . $image_name ;
                echo 'image add location: '. $realtive_image_path;
                $db->updateCategory(null, $cat_id, null, null, $realtive_image_path, null); 
            }
        }


        $db->close();  
    }

// upload the image to 'assets/img/cat/' folder 
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . $target_project . $cat_img_folder;
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

// resize image resulation
    // if($uploadOk == 1){
    //     $img = new Image();
    //     $img->resize(800, 800, $target_file);
    // }

   
}

    $_SESSION['message'] = $message;

    if($categoryId != null){
        // Redirect back to CategoryListPage
    header("Location: ../category_list.php");

    }
    else{
    // Redirect back to addCategory.php
    header("Location: ../add_category.php");
    }
    exit(); // Ensure no further code is executed

?>