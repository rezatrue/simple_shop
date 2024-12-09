<?php

// Check for messages and display them
if (isset($_SESSION['message'])) {
    echo "<div class='message'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']); // Clear the message after displaying it
  }
  
// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

$page = 1;
if (isset($_GET['page'])) 
    $page = $_GET['page'];

$totalItems = 0; // Total number of items
$itemsPerPage = 10; // Items per page  

// Create an instance of the Database class
$db = new Database();

if(isset($_GET['cat'])){                       
    $result = $db->queryForRelatedProduct('admin', $cat_id, $page, $itemsPerPage);
    $totalItems = $db->productCountForCat('admin', $cat_id); 
}else{
    $result = $db->queryForListPage('admin', $page, $itemsPerPage);
    $totalItems = $db->queryCountForListPage('admin');
}

// Close the database connection
$db->close();
        
?>

<!-- general form elements -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Product List (total: <?php echo $totalItems; ?>)</h3>
  </div>
  <!-- /.card-header -->
<!-- table start -->
    <div class="table-responsive">
       <table class="table table-striped table-hover w-auto">
          <thead class="table-light">
            <tr>
            <th><h5>ID</h5></th>
            <th><h5>Update</h5></th>
            <th><h5>Name</h5></th>
            <th><h5>Image</h5></th>
            <th><h5>Price</h5></th>
            <th><h5>Sizes</h5></th>
            <th><h5>Show/Hide</h5></th>
            <th><h5>Featured</h5></th>
            <th><h5>Description</h5></th>
            <th><h5>Spacification</h5></th>
            <th><h5>Action</h5></th>
            </tr>
          </thead>

          <tbody>
                    <?php 
                        if (!empty($result['product'])) {
                            foreach ($result['product'] as $row) {
                                include('./pages/admin/product_single_row.php');
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
          </tbody>
       </table>
    </div>
<!-- table end-->
<!-- pagiantion start -->
                <?php
                    // Sample data (replace with your actual data source)

                    $totalPages = ceil($totalItems / $itemsPerPage); // Total number of pages

                    // Get the current page from the URL, default to 1 if not set
                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                    // Ensure current page is within valid range
                    if ($currentPage < 1) {
                        $currentPage = 1;
                    } elseif ($currentPage > $totalPages) {
                        $currentPage = $totalPages;
                    }

                    // Calculate the offset for the SQL query (if applicable)
                    $offset = ($currentPage - 1) * $itemsPerPage;

                ?>
                <div class="row">
                    <ul class="pagination pagination-lg justify-content-end">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link active rounded-0 mr-3 shadow-sm" href="?page=<?php echo $currentPage - 1; ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php
                        // Display first page
                        if ($totalPages > 1) {
                            echo '<li class="page-item ' . ($currentPage === 1 ? 'active' : '') . '">';
                            echo '<a class="page-link rounded-0 mr-3 shadow-sm" href="?page=1">1</a>';
                            echo '</li>';
                        }

                        // Display ellipsis if needed
                        if ($totalPages > 4 && $currentPage > 3) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }

                        // Display pages around current page
                        for ($i = max(2, $currentPage - 1); $i <= min($totalPages - 1, $currentPage + 1); $i++) {
                            echo '<li class="page-item ' . ($currentPage === $i ? 'active' : '') . '">';
                            echo '<a class="page-link rounded-0 mr-3 shadow-sm" href="?page=' . $i . '">' . $i . '</a>';
                            echo '</li>';
                        }

                        // Display last page if needed
                        if ($totalPages > 3 && $currentPage < $totalPages - 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            echo '<li class="page-item ' . ($currentPage === $totalPages ? 'active' : '') . '">';
                            echo '<a class="page-link rounded-0 mr-3 shadow-sm" href="?page=' . $totalPages . '">' . $totalPages . '</a>';
                            echo '</li>';
                        }

                        if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link rounded-0 mr-3 shadow-sm" href="?page=<?php echo $currentPage + 1; ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- pagiantion ends -->



