<?php

// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

// Create an instance of the Database class
$db = new Database();

if (isset($_GET['id'])) 
    $o_id = $_GET['id'];
if (isset($_GET['amt'])) 
    $o_total = $_GET['amt'];

if(isset($_GET['id']))    
    $result = $db->orderDetails($o_id);

// Close the database connection
$db->close();
        
?>

<!-- general form elements -->
<div class="card card-primary">
  <!-- <div class="card-header"> -->
    
    <div class="card-header d-flex justify-content-between align-items-center text-white">    
            <h3 class="card-title">
            <?php if(isset($_GET['id']))
                      echo 'Order ID : '. $o_id . ' ';
                  if(isset($_GET['amt']))
                      echo '[Total: <strong id="total">'. $o_total . '</strong> ]';
                  if(!isset($_GET['id']))
                      echo 'Add items';
            ?></h3>                 
            <button class="btn btn-success btn-sm" type="submit" name="submit" value="remove">Close</button>
    </div>
  <!-- </div> -->
  <!-- /.card-header -->
<!-- table start -->
    <div class="table-responsive">
       <table class="table table-striped table-hover w-auto">
          <thead class="table-light">
            <tr>
            <th><h5>Total Amount</h5></th>    
            <th><h5>Product Id</h5></th>
            <th><h5>Name</h5></th>
            <th><h5>Unit price</h5></th>
            <th><h5>Order Units</h5></th>
            <th><h5>Sizes</h5></th>
            <th><h5>Customer Notes</h5></th>
            <th><h5>No/OFF</h5></th>
            </tr>
          </thead>

          <tbody>
                    <?php 
                        if (!empty($result['item'])) {
                            foreach ($result['item'] as $row) {
                                include('./pages/admin/order_details_single_row.php');
                            }
                        } else {
                            echo "0 results";
                        }
                    ?>
          </tbody>
       </table>
    </div>
<!-- table end-->




