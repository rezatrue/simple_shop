<?php
// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

$data = null;

if (isset($_GET['id'])){
	$productId = $_GET['id'];
	$db = new Database();
	$result = $db->getDetails($productId);
	//$data = $db->fetchAll($result);
	$data = mysqli_fetch_assoc($result);
	//echo $data['p_image'];
	$db->close();
	} 

if (isset($_POST['CategoryName']) && $_POST['CategoryName'] != null){
	$categoryName = $_POST['CategoryName'];
	$categoryOfMonth = $_POST['CategoryOfMonth'];
	$categoryImage = $_POST['CategoryImage'];
	
	echo $categoryName;
	echo $categoryImage;
	echo $categoryOfMonth;
	} 

?>

<!-- general form elements -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><?php if($data != null) echo "Edit Product (ID: ". $productId . ")"; else echo "Add Product"; ?></h3>
  </div>
  <!-- /.card-header -->

<!-- form start -->
<form action="add_product.php" method="post">
<div class="card-body">

        <div class="form-group">
          <label for="ProductName">Product Name</label>
          <input type="text" class="form-control" id="ProductName" name="ProductName" placeholder="Product Name" <?php if($data != null && $data['p_name'] != null) echo 'value="'. htmlspecialchars($data['p_name']) . '"'; ?>>
        </div>

        <div class="form-group">
          <label for="exampleInputFile">Category image</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile">
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
        </div>


        <div class="form-group">
          <label for="ProductPrice">Product Price</label>
          <input type="text" class="form-control" id="ProductPrice" name="ProductPrice" placeholder="Product Price" <?php if($data != null && $data['p_price'] != null) echo 'value="'. htmlspecialchars($data['p_price']) . '"'; ?>>
        </div>


        <div class="form-group">
          <label for="ProductSize">Product Size</label>
          <input type="text" class="form-control" id="ProductSize" name="ProductSize" placeholder="Product Size" <?php if($data != null && $data['p_sizes'] != null) echo 'value="'. htmlspecialchars($data['p_sizes']) . '"'; ?>>
        </div>

        <div class="form-group">
          <label for="ProductGender">Gender</label>
          <input type="text" class="form-control" id="ProductGender" name="ProductGender" placeholder="Product Gender" <?php if($data != null && $data['p_gender'] != null) echo 'value="'. htmlspecialchars($data['p_gender']) . '"'; ?>>
        </div>

	<div class="form-group">
          <label for="ProductCategory">Product Category</label>
          <input type="text" class="form-control" id="ProductCategory" name="ProductCategory" placeholder="Product Category" <?php if($data != null && $data['p_category'] != null) echo 'value="'. htmlspecialchars($data['p_category']) . '"'; ?>>
        </div>

        <div class="form-group">
          <label class="form-check-label" for="IsFeatured"><b>Is Featured</b></label> 
          <input type="checkbox" style="margin-left:10px; margin-top:8px;" class="form-check-input" id="IsFeatured" name="IsFeatured" <?php if($data != null && $data['p_is_featured'] != 0) echo "checked"; ?>>
        </div>

	<div class="form-group">
          <label for="ProductDescription">Product Description</label>
          <input type="text" class="form-control" id="ProductDescription" name="ProductDescription" placeholder="Product Description" <?php if($data != null && $data['p_short_description'] != null) echo 'value="'. htmlspecialchars($data['p_short_description']) . '"'; ?>>
        </div>

	<div class="form-group">
          <label for="ProductSpecification">Product Specification</label>
          <input type="text" class="form-control" id="ProductSpecification" name="ProductSpecification" placeholder="Product Specification" <?php if($data != null && $data['p_specification'] != null) echo 'value="'. htmlspecialchars($data['p_specification']) . '"'; ?>>
        </div>

      	<div class="form-group">
      	    <input type="submit" value="<?php if($data != null) echo "Update"; else echo "Add"; ?>">
      	</div>

</div>
<!-- /.card-body -->
</form>