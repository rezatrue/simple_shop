
<script src="assets/js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: '#ProductSpecification',
    license_key: 'gpl'
  });
</script>
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

.custom-image {
      position: relative;
      display: inline-block;
  }
  .remove-image {
      position: absolute;
      top: 0;
      right: 0;
      background-color: red;
      color: white;
      border: none;
      cursor: pointer;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      display: none; /* Initially hidden */
  }
  .image-preview {
      max-width: 150px; 
      max-height: 150px; 
  }
</style>


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
// echo '<pre>';
// print_r($productDetails);
// echo '</pre>';
// exit();
?>


<!-- general form elements -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><?php if($productDetails != null) echo "Edit Product (ID: ". $productId . ")"; else echo "Add Product"; ?></h3>
  </div>
  <!-- /.card-header -->

<!-- form start -->
<form id="addProductForm" action="./data/product_upload.php" method="post" enctype="multipart/form-data" >
<div class="card-body">

        <input type="hidden" id="id" name="id" <?php if($productDetails != null && $productDetails['p_id'] != null) echo 'value="'. htmlspecialchars($productDetails['p_id']) . '"'; ?> required>

        <div class="form-group">
          <label for="ProductName">Product Name</label>
          <input type="text" class="form-control" id="ProductName" name="ProductName" placeholder="Product Name" <?php if($productDetails != null && $productDetails['p_name'] != null) echo 'value="'. htmlspecialchars($productDetails['p_name']) . '"'; else echo 'value=""'; ?> required>
        </div>

    <div class="row">
      <div class="col-12 col-sm-6">
        <div class="form-group">
            <label for="exampleInputFile">Product images</label>
              <!-- -->
              <div class="row">
                
                <?php
                  for ($x = 1; $x <= 9; $x++) {
                    include('product_images.php');
                  }
                ?>

              </div>
              <!-- -->
        </div>
      </div>
      <div class="col-12 col-sm-6">
        <div class="form-group">
          <label for="ProductPrice">Product Price</label>
          <input type="number" step="0.001" class="form-control" id="ProductPrice" name="ProductPrice" placeholder="Product Price" <?php if($productDetails != null && $productDetails['p_price'] != null) echo 'value="'. htmlspecialchars($productDetails['p_price']) . '"'; else echo 'value=""'; ?> required>
        </div>

        <div class="form-group">
          <label for="ProductSize">Product Size</label>
          <input type="text" class="form-control" id="ProductSize" name="ProductSize" placeholder="Product Size" <?php if($productDetails != null && $productDetails['p_sizes'] != null) echo 'value="'. htmlspecialchars($productDetails['p_sizes']) . '"'; else echo 'value=""'; ?> required>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6">
              <div class="form-group">
                <label class="form-check-label" for="IsShow"><b>Show</b></label> 
                <input type="checkbox" style="margin-left:10px; margin-top:8px;" class="form-check-input" id="IsShow" name="IsShow" <?php if($productDetails != null && $productDetails['p_is_show'] == 1) echo "checked"; if(!$productDetails) echo "checked";?>>
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
          <div id="errorMessage" class="text-danger"></div>
          <div id="selectedCategories">
          <?php 
                if (!empty($productDetails['categories'])) {
                  foreach ($productDetails['categories'] as $category) {
                  echo '<div class="selected-item">'; 
                  echo '<input type="hidden" name="selected-category[]" value="' . htmlspecialchars($category["sub_cat_id"]) . '" required>'; 
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
          <textarea class="form-control" id="ProductSpecification" name="ProductSpecification" placeholder="Product Specification" ><?php if($productDetails != null && $productDetails['p_specification'] != null) echo htmlspecialchars($productDetails['p_specification']); ?></textarea>
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

document.addEventListener('DOMContentLoaded', function() {
    const addFrom = document.getElementById('addProductForm');
    addFrom.addEventListener('submit', function(event) {
      // Get all hidden inputs with name 'selected-category[]'
      var selectedCategories = document.getElementsByName('selected-category[]');

     // Check if at least one category is selected
      var isSelected = false;
      for (var i = 0; i < selectedCategories.length; i++) {
          if (selectedCategories[i].value) { // Check if the value is set
              isSelected = true;
              break;
          }
      }

      // If no category is selected, prevent submission and show an error message
      if (!isSelected) {
          event.preventDefault(); // Prevent form submission
          document.getElementById('errorMessage').innerText = 'Please select at least one category.';
          document.getElementById('errorMessage').style.display = 'block'; // Show error message
      } else {
          document.getElementById('errorMessage').style.display = 'none'; // Hide error message if valid
      }
  }); 

});
</script>