<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<?php
// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

$page = 1;
if (isset($_GET['page'])) 
    $page = $_GET['page'];

if (isset($_GET['o_id'])) 
    $like_o_id = $_GET['o_id'];    

$totalItems = 0; // Total number of items
$itemsPerPage = 10; // Items per page  

// Create an instance of the Database class
$db = new Database();

if (isset($_GET['o_id'])){
    $result = $db->partialConfirmOrderIdListPage($like_o_id, $page, $itemsPerPage);
    $totalItems = $db->countForPartialConfirmOrderIdListPage($like_o_id);
} else{
    $result = $db->confirmOrderListPage($page, $itemsPerPage);
    $totalItems = $db->countForConfirmOrderListPage();
}

// Close the database connection
$db->close();
        
?>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- general form elements -->
<!-- <div class="table-responsive mb-2">
    <tbody>
        <tr> -->
            <form action="confirm_orders.php" method="get" id="searchForm">
            <div class="row">
                <div class="col-12 col-sm-4">
                    Order ID : <input id="o_id" name="o_id"></input>
                </div>
                <!-- <div class="col-12 col-sm-4">
                    Date : <input type="text" id="Date" name="Date" class="form-control datepicker" placeholder="Select Date" autocomplete="off">
                </div>  -->
                <div class="col-12 col-sm-4"> 
                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                </div>    
            </div>
            </from>
        <!-- </tr>
    </tbody>
</div> -->
<div class="card card-primary">  
  <div class="card-header">
    <h3 class="card-title">Order List (total: <?php echo $totalItems; ?>)</h3>
  </div>
  <!-- /.card-header -->
<!-- table start "col-md-8 mx-auto"-removedform div class -->
    <div class="table-responsive"> 
       <table class="table table-striped table-hover w-auto">
          <thead class="table-light">
            <tr>
            <th><h5>ID</h5></th>
            <th><h5>Details</h5></th>
            <th><h5>Date_Time</h5></th>
            <th><h5>Items</h5></th>
            <th><h5>Address</h5></th>
            <th><h5>Notes</h5></th>
            <th><h5>Delivery</h5></th>
            </tr>
          </thead>
          
          <tbody>
                    <?php 
                        if (!empty($result['order'])) {
                            foreach ($result['order'] as $row) {
                                include('./pages/admin/confirm_order_list_single_row.php');
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


