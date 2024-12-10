<?php

// Check for messages and display them
if (isset($_SESSION['message'])) {
    echo "<div class='message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']); // Clear the message after displaying it
}

// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

$productDetails = null;

if (isset($_GET['id'])){
	$productId = $_GET['id'];
	$db = new Database();
  $productDetails = $db->getProductDetails($productId);
  $db->close();
}

?>

<style>
  .dropdown-menu {
      border: 1px solid #ccc;
      max-height: 150px;
      overflow-y: auto;
  }

  .category-item, .subcategory-item {
      padding: 10px;
      cursor: pointer;
  }

  .category-item:hover, .subcategory-item:hover {
      background-color: #f0f0f0;
  }

  .selected-item{
      display: inline-block;
      margin: 0px 5px 5px 5px;
      padding: 0px 5px 0px 5px;
      background-color:powderblue;
      text-transform: capitalize;
  }
</style>


<!-- general form elements -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><?php if($productDetails != null) echo "Edit Product (ID: ". $productId . ")"; else echo "Add Product"; ?></h3>
  </div>
  <!-- /.card-header -->

<!-- form start -->
<form action="./data/product_upload.php" method="post" enctype="multipart/form-data" >
<div class="card-body">

        <input type="hidden" id="id" name="id" <?php if($productDetails != null && $productDetails['p_id'] != null) echo 'value="'. htmlspecialchars($productDetails['p_id']) . '"'; ?> required>

        <div class="form-group">
          <label for="ProductName">Product Name</label>
          <input type="text" class="form-control" id="ProductName" name="ProductName" placeholder="Product Name" <?php if($productDetails != null && $productDetails['p_name'] != null) echo 'value="'. htmlspecialchars($productDetails['p_name']) . '"'; ?>>
        </div>

    <div class="row">
      <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="exampleInputFile">Category image</label>
            <div class="custom-image">
                <div id="preview" style="padding-bottom: 10px;">
                  <?php if($productDetails != null && $productDetails['p_image'] != null) echo '<img src='. $productDetails['p_image'] .' alt="Image Preview" style="max-width: 300px; max-height: 300px;" />' ; ?>
                </div>
            </div>    
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile" name="CategoryImage" accept=".jpg, .jpeg, .png, .gif">
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
        </div>
      </div>
      <div class="col-12 col-sm-6">
        <div class="form-group">
          <label for="ProductPrice">Product Price</label>
          <input type="text" class="form-control" id="ProductPrice" name="ProductPrice" placeholder="Product Price" <?php if($productDetails != null && $productDetails['p_price'] != null) echo 'value="'. htmlspecialchars($productDetails['p_price']) . '"'; ?>>
        </div>

        <div class="form-group">
          <label for="ProductSize">Product Size</label>
          <input type="text" class="form-control" id="ProductSize" name="ProductSize" placeholder="Product Size" <?php if($productDetails != null && $productDetails['p_sizes'] != null) echo 'value="'. htmlspecialchars($productDetails['p_sizes']) . '"'; ?>>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label class="form-check-label" for="IsShow"><b>Show</b></label> 
                <input type="checkbox" style="margin-left:10px; margin-top:8px;" class="form-check-input" id="IsShow" name="IsShow" <?php if($productDetails != null && $productDetails['p_is_show'] == 1) echo "checked"; ?>>
              </div>
            </div>
            <div class="col-12 col-sm-6">
              <div class="form-group">
                  <label class="form-check-label" for="IsFeatured"><b>Is Featured</b></label> 
                  <input type="checkbox" style="margin-left:10px; margin-top:8px;" class="form-check-input" id="IsFeatured" name="IsFeatured" <?php if($productDetails != null && $productDetails['p_is_featured'] != 0) echo "checked"; ?>>
              </div>
            </div>
         </div>

        <!--  Caregory area starts here -->
        <div class="form-group">
          <label for="ProductCategory">Product Categories</label>
          <div id="selectedCategories">
          <?php 
                if (!empty($productDetails['categories'])) {
                  foreach ($productDetails['categories'] as $category) {
                  echo '<div class="selected-item">'; 
                  echo '<input type="hidden" name="selected-category[]" value="' . htmlspecialchars($category["sub_cat_id"]) . '">'; 
                  if(!empty($category['parent_cat'])) echo htmlspecialchars($category['parent_cat']) . " -> ";
                  echo htmlspecialchars($category['cat_name']) . ' <span class="remove-item">&times;</span>';
                  echo "</div>";
                  }
                }
            ?>
          </div>
          <div class="row">
            <div class="col-12 col-sm-6">
                  <br/>
                  <label for="ProductCategory">Add New Category</label>
                  <input type="text" class="form-control" id="ProductCategory" name="ProductCategory" placeholder="Product Category">
                  <div id="categoryList" class="dropdown-menu"></div>
            </div>
            <div class="col-12 col-sm-6">
                  <br/>
                  <label for="SubCategory">& Sub Category</label>
                  <input type="text" class="form-control" id="SubCategory" name="SubCategory" placeholder="Sub Category">
                  <div id="subCategoryList" class="dropdown-menu"></div>
            </div>
          </div>
        </div>
        <!--  Caregory area ends here -->
      </div>
    </div>



	<div class="form-group">
          <label for="ProductDescription">Product Description</label>
          <input type="text" class="form-control" id="ProductDescription" name="ProductDescription" placeholder="Product Description" <?php if($productDetails != null && $productDetails['p_short_description'] != null) echo 'value="'. htmlspecialchars($productDetails['p_short_description']) . '"'; ?>>
        </div>

	<div class="form-group">
          <label for="ProductSpecification">Product Specification</label>
          <input type="text" class="form-control" id="ProductSpecification" name="ProductSpecification" placeholder="Product Specification" <?php if($productDetails != null && $productDetails['p_specification'] != null) echo 'value="'. htmlspecialchars($productDetails['p_specification']) . '"'; ?>>
        </div>

      	<div class="form-group">
      	    <input type="submit" value="<?php if($productDetails != null) echo "Update"; else echo "Add"; ?>">
      	</div>

</div>
<!-- /.card-body -->
 <!-- iamges test-->

<!-- iamges test-->
</form>


<script>
    const fileInput = document.getElementById('customFile');
    const preview = document.getElementById('preview');

    $(document).ready(function() {
        // Load categories on input
        $('#ProductCategory').on('input', function() {
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
            $('#ProductCategory').val(category);
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
            let category = $('#ProductCategory').val();
            let categoryid = $(this).get(0).getAttribute('value');
            let subcategory = $(this).text();
            $('#SubCategory').val('');
            $('#ProductCategory').val('');
            $('#subCategoryList').hide();
            
            // Display selected categories and subcategories
            $('#selectedCategories').append(`
                <div class="selected-item">
                  <input type="hidden" name="selected-category[]" value="${categoryid}">
                  ${category} -> ${subcategory} <span class="remove-item">&times;</span>
                </div>
            `);
        });

        // Remove selected item
        $(document).on('click', '.remove-item', function() {
            $(this).parent().remove();
        });
    });

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

// .... image test........

</script>