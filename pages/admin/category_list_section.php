<?php

// Check for messages and display them
if (isset($_SESSION['message'])) {
  echo "<div class='message'>" . $_SESSION['message'] . "</div>";
  unset($_SESSION['message']); // Clear the message after displaying it
}

// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

// Create an instance of the Database class
$db = new Database();

$categories = $db->getCategoryList(); 
$totalItems = count($categories);  
            
// Close the database connection
$db->close();
?>

<!-- general form elements -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Category List (total: <?php echo $totalItems; ?>)</h3>
  </div>
  <!-- /.card-header -->
<!-- table start -->
    <div class="table-responsive">
       <table class="table table-striped table-hover w-auto">
          <thead class="table-light">
            <tr>
            <th><h5>ID</h5></th>
            <th><h5>Update</h5></th>
            <th><h5>Category</h5></th>
            <th><h5>Sub Category</h5></th>
            <th><h5>Category of the Month</h5></th>
            <th><h5>Image</h5></th>
            <th><h5>Action</h5></th>
            </tr>
          </thead>

          <tbody>
              <?php
		// Check if there are results and fetch data
		if ($totalItems > 0) {
 		// Iterate through each row using foreach loop
 		foreach ($categories as $row) {
		   include ('./pages/admin/category_single_row.php');
		}
		} else {
		   echo "0 results";
		  }
		?>
          </tbody>
       </table>
    </div>
<!-- table end-->



