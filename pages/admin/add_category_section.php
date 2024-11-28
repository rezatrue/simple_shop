<?php

// Check for messages and display them
if (isset($_SESSION['message'])) {
    echo "<div class='message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']); // Clear the message after displaying it
}

// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

$category = null;

if (isset($_GET['id'])){
	$subcategoryId = $_GET['id'];
	$db = new Database();
	$category = $db->getSubCategoryDetails($subcategoryId);
	$db->close();
	} 

if (isset($_POST['CategoryName']) && $_POST['CategoryName'] != null){
    $categoryName = $_POST['CategoryName'];
    echo $categoryName;
  }

?>

<!-- general form elements -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><?php if($category != null) echo "Edit Category (ID: ". $subcategoryId . ")"; else echo "Add Category"; ?></h3>
  </div>
  <!-- /.card-header -->

<!-- form start -->
<!--form action="add_category.php" method="post" enctype="multipart/form-data"-->
<form action="./data/category_upload.php" method="post" enctype="multipart/form-data" >
<div class="card-body">
        
        <input type="hidden" id="id" name="id" <?php if($category != null && $category['sub_cat_id'] != null) echo 'value="'. htmlspecialchars($category['sub_cat_id']) . '"'; ?>>
  <div class="row">
    <div class="col-12 col-sm-6">
        <div class="form-group">
          <label for="CategoryName">Category Name</label>
          <input type="text" class="form-control" id="CategoryName" name="CategoryName" placeholder="Category Name" <?php if($category != null && $category['cat_name'] != null) echo 'value="'. htmlspecialchars($category['cat_name']) . '"'; ?> required >
          <div id="categoryList" class="dropdown-menu"></div>
        </div>

        <div class="form-group">
          <label for="SubCategoryName">Sub Category</label>
          <input type="text" class="form-control" id="SubCategoryName" name="SubCategoryName" placeholder="Sub Category Name" <?php if($category != null && $category['sub_cat_name'] != null) echo 'value="'. htmlspecialchars($category['sub_cat_name']) . '"'; ?> required >
          <div id="subCategoryList" class="dropdown-menu"></div>
        </div>

        <div class="form-group">
          <label class="form-check-label" for="CategoryOfMonth"><b>Category Of The Month</b></label> 
          <input type="checkbox" style="margin-left:10px; margin-top:8px;" class="form-check-input" id="CategoryOfMonth" name="CategoryOfMonth" <?php if($category != null && $category['cat_is_catofmonth'] != 0) echo " value='1' checked";?>>
        </div>


    </div>
    <div class="col-12 col-sm-6">
        <div class="form-group">
          <label for="exampleInputFile">Category image</label>
            <div class="custom-image">
                <div id="preview" style="padding-bottom: 10px;">
                  <?php if($category != null && $category['cat_image'] != null) 
                          echo '<img height="90px" weight="90px" src='. $category['cat_image'] . ' alt="Image Preview" />' ; 
                        else
                          echo '<img src="assets/img/cat/dumy.png" alt="Image Preview" style="max-width: 100px; max-height: 100px;" />' ; 
                  ?>
                </div>
            </div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile" name="CategoryImage" accept=".jpg, .jpeg, .png, .gif">
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
        </div>
      </div>
  </div>
  <div class="form-group" >
      <input type="submit"  value="<?php if($category != null) echo "Update"; else echo "Add"; ?>">
  </div>

</div>
<!-- /.card-body -->
</form>


<script>

// category load
  $(document).ready(function() {
        // Load categories on input
        $('#CategoryName').on('input', function() {
            let query = $(this).val();
            if (query.length > 2) {
                $.ajax({
                    url: './data/fetch_categories.php',
                    method: 'GET',
                    data: { query: query },
                    success: function(data) {
                        $('#categoryList').html(data).show();
                    }
                });
            } else {
                $('#categoryList').hide();
            }
        });

        // Select a category
        $(document).on('click', '.category-item', function() {
            let category = $(this).text();
            $('#CategoryName').val(category);
            $('#categoryList').hide();
            
            // Load subcategories
            $.ajax({
                url: './data/fetch_subcategories.php',
                method: 'GET',
                data: { category: category },
                success: function(data) {
                    $('#subCategoryList').html(data).show();
                }
            });
        });

        // Select a subcategory
        $(document).on('click', '.subcategory-item', function() {
            let subcategory = $(this).text();
            $('#SubCategoryName').val(subcategory);
            $('#subCategoryList').hide();
        });
        
    });
  // image file validation
    const fileInput = document.getElementById('customFile');
    const preview = document.getElementById('preview');
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const img = new Image();
            img.src = URL.createObjectURL(file);
            
            img.onload = function() {
                const width = img.naturalWidth;
                const height = img.naturalHeight;

                // Check for minimum dimensions
                if (width < 800 || height < 800) {
                    alert("Image dimensions must be at least 800x800 pixels.");
                    fileInput.value = ''; // Clear the input
                    preview.innerHTML = ''; // Clear the preview
                }else if (file.size < 100 * 1024) {
                    alert("File size must be greater than 100KB.");
                    fileInput.value = ''; // Clear the input
                    preview.innerHTML = ''; // Clear the preview
                }else {
                    // Display the image preview
                    preview.innerHTML = `<img src="${img.src}" alt="Image Preview" style="max-width: 300px; max-height: 300px;" />`;
                }
            };

            img.onerror = function() {
                alert("There was an error loading the image.");
            };
        }
    });
</script>