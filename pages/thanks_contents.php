<?php

// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

$show_msg = 0;
$order_id = null;
if(isset($_GET['order_id'])){
    $order_id = $_GET['order_id'];
    $db = new Database();
    $deliverydetails = $db->getDeliveryDetails($order_id);
    $db->close();
}
    
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = '';
    if (isset($_POST['name']))
        $name = $_POST['name'];
    if (isset($_POST['o_id']))
        $o_id = $_POST['o_id'];
    if (isset($_POST['phone']))
        $phone = $_POST['phone'];
    $address = '';
    if (isset($_POST['address']))
        $address = $_POST['address'];
    $notes = '';
    if (isset($_POST['notes']))
        $notes = $_POST['notes'];
 
    if (isset($_POST['o_id']) && $_POST['o_id'] != null && isset($_POST['phone']) && $_POST['phone'] != null ){
        $db = new Database();
        $result = $db->updateDeliveryDetails($o_id, $name, $phone, $address, $notes);
        $db->close();
        if($result)
            $show_msg = 1;
        else
            $show_msg = 0;
    }else{
        $show_msg = 0;
    }
    
}

?>

    <!-- Start Content -->
    <div class="container py-5">
        <div class="row">
<!--  side bar starts here -->
            <div class="col-lg-3">
            <h1 class="h5 pb-2">For more go to Categories</h1>
                <ul class="list-unstyled templatemo-accordion">
                    <?php
                        $db = new Database();
                        $data = $db->getCategoryList(); 
                        $db->close();
                        if (!empty($data)){
                            $lastId = 0;
                            $cat_subcat = [];
                            $subcats = [];
                            foreach ($data as $row) {
                                if ($lastId == 0){
                                    $lastId = $row['cat_id'];
                                    $cat_subcat[] =  ['cat_name'=>$row['cat_name']];
                                    $subcats[] = ['sub_cat_name'=>$row['sub_cat_name'], 'sub_cat_id'=>$row['sub_cat_id']] ;
                                }
                                elseif ($lastId == $row['cat_id']){
                                    $subcats[] = ['sub_cat_name'=>$row['sub_cat_name'], 'sub_cat_id'=>$row['sub_cat_id']] ;
                                }     
                                elseif ($lastId != $row['cat_id']){
                                    $cat_subcat[] =  ['subcats'=>$subcats ];
                                    include('single_category_menu.php');

                                    $cat_subcat = [];
                                    $subcats = [];
                                    $lastId = $row['cat_id'];
                                    $cat_subcat[] =  ['cat_name'=>$row['cat_name']];
                                    $subcats[] = ['sub_cat_name'=>$row['sub_cat_name'], 'sub_cat_id'=>$row['sub_cat_id']] ;
                                }

                            }
                            $cat_subcat[] =  ['subcats'=>$subcats ];
                            include('single_category_menu.php');
                        } 
                    ?>
                </ul>    
            </div>
<!--  side bar ends here -->

            <div class="col-lg-9">
                <?php if($show_msg === 1){ 
                    echo '<div class="row">
                            <div class="col-md-6">
                                <ul class="list-inline shop-top-menu pb-3 pt-1">
                                    <li class="list-inline-item">
                                        <h2 class="mr-3 text-success" >Thank you : </h2>
                                        <p class="text-success"><strong> Your order is now placed to our system.</strong></p>
                                    </li>
                                </ul>
                            </div>
                        </div>';
                    } ?>

                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Please Enter Delivery Details</h3>
                            </div>
                            <!-- login form start -->
                            <form action="thank_you.php" method="post">
                                <div class="card-body">
                                    <input type="hidden" class="form-control" id="o_id" name="o_id" value="<?php if($order_id) echo $order_id; ?>" required>
                                <div class="form-group">
                                    <label for="Username">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Your Name" value="<?php if(isset($deliverydetails[0]['o_name']) && $deliverydetails[0]['o_name'] != null) echo $deliverydetails[0]['o_name'];?>">
                                </div>
                                <div class="form-group">
                                    <label for="Password">Phone*</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Contact Phone" value="<?php if(isset($deliverydetails[0]['o_phone']) && $deliverydetails[0]['o_phone'] != null) echo $deliverydetails[0]['o_phone'];?>" required >
                                </div>
                                <div class="form-group">
                                    <label for="Password">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Dalivery Address" value="<?php if(isset($deliverydetails[0]['o_address']) && $deliverydetails[0]['o_address'] != null) echo $deliverydetails[0]['o_address'];?>">
                                </div>
                                <div class="form-group">
                                    <label for="Password">Notes</label>
                                    <input type="text" class="form-control" id="notes" name="notes" placeholder="Enter notes" value="<?php if(isset($deliverydetails[0]['o_notes']) && $deliverydetails[0]['o_notes'] != null) echo $deliverydetails[0]['o_notes'];?>">
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Submit">
                                </div>            
                                </div>
                            </form>  
                            <!-- login form ends -->
                        </div>
                    </div>
                </div>
            <!-- page contents starts -->    
                <div class="row mr-3">            
                    <p>OR<p>
                    <p>Contact to 01700000000 with delivery details to Confirm your Order. <br>
                    <p>Please do mention your order number <strong><?php if($order_id) echo $order_id; ?></strong>.</p>
                    <p>Go back for Shipping -> <a href='shop.php'>Click here</p>
                </div>
                
            <!-- page contents ends -->     

            </div>  

        </div>
    </div>
    <!-- End Content -->
