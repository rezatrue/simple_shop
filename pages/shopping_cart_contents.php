<style>
    .page-item.active .page-link{
    background-color: #69bb7e;
    }
</style>    

<?php

// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

$totalItems = 0;
$totalCost = 0;

?>

<!-- Start Content -->
<div class="container py-5">
    <div class="row">

        <div class="col-lg-9">     
            <!-- Voice List section starts -->
            <div class="row">
                <div class="col-12 col-sm-6">
                <div class="card card-primary p-1">
                    <div class="card-header bg-success text-white">
                        <div class="row">
                            <div class="col text-center">
                                <h3 class="card-title mb-1">Upload List</h3>
                            </div>
                        </div>
                    </div>   
                </div>    
                </div>
                <div class="col-12 col-sm-6">
                <div class="card card-primary p-1">
                    <div class="card-header bg-success text-white">
                        <div class="row">
                            <div class="col text-center">
                                <h3 class="card-title mb-1">Add Voice</h3>
                            </div>
                        </div>
                    </div> 
                </div>    
                </div>
            </div>          
            <!-- Voice List section ends -->     
            <!-- product carts starts -->
                <div class="row">            
                    <?php 

                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $id => $item) {
                            $totalItems = $totalItems + (int)$item['quantity'];
                            $cost = (float)$item['price'] * (int)$item['quantity'];
                            $totalCost = $totalCost + $cost;
                            include('single_shopping_item.php');
                        }
                    }else {
                        echo "0 results";
                    }
                    
                    ?>
                </div>
            <!-- product carts ends -->     
                <!-- <total counts> -->
                <div class="row">
                    <div class="col-12">
                        <div class="card position-relative">
                            
                            <?php if($totalItems > 0){
                                    echo '<div class="card-header d-flex justify-content-between align-items-center bg-success text-white">';
                                    echo '<h5 class="mb-0">Total Item: <string id="total-items">' . $totalItems . '</string> &   Total Cost: <string id="total-costs">' . $totalCost . '</string></h5>';  
                                    echo '<form action="./data/submit_order.php" method="POST">';                                     
                                    echo '<button class="btn btn-primary btn-lg text-center" type="submit" name="submit" value="order">Submit</button>';
                                    echo '</form>';
                                    echo '</div>';
                                    echo '<div class="mb-0">* Voice & Upload list items price will be added to the total price</div>'; 
                                }
                                else{
                                    echo '<div class="card-header bg-secondary text-white">';
                                    echo '<h5 class="mb-0 text-center">No Item is added to the cart.</h5>';
                                    echo '</div>';
                                }
                            ?>
                        </div>
                          
                    </div>   
                </div>
                <!-- <total counts> -->
        </div>
        <!--  side bar starts here -->
        <div class="col-lg-3">
        <h1 class="h4 pb-2">Categories</h1>
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


    </div>
</div>
<!-- End Content -->

<script>

function changeQuantity(id, change) {

    // function don't do anything if minus us clicked & only one product in the display
    const numberOfProductDisplay = document.getElementById('var-value-' + id).textContent;
    if(numberOfProductDisplay == '1' && change == '-1')
        return;

    // Get the corresponding elements using the unique ID
    const quantityInput = document.getElementById('product-quanity-' + id);
    const quantityDisplay = document.getElementById('var-value-' + id);
    const totalPriceDisplay = document.getElementById('total-price-' + id);
    //const unitPrice = document.getElementById('unit-price-' + id).textContent;
    const totalItem = document.getElementById('total-items');
    const totalItems = parseInt(totalItem.textContent, 10); // Use parseFloat if dealing with decimals
    const totalCost = document.getElementById('total-costs');
    const totalCosts = parseInt(totalCost.textContent, 10); // Use parseFloat if dealing with decimals
    const unitPrice = document.getElementById('unit-price-' + id);
    const unitPrices = parseInt(unitPrice.textContent, 10); // Use parseFloat if dealing with decimals

    // Get the current value and convert it to a number
    let currentValue = parseInt(quantityDisplay.textContent, 10);

    // Update the value based on the change
    currentValue += change;

    // Ensure the quantity does not go below 1
    if (currentValue < 1) {
        currentValue = 1;
    }

    // Update the display and hidden input
    quantityDisplay.textContent = currentValue;
    quantityInput.value = currentValue; // Update hidden input value
    totalPriceDisplay.textContent = currentValue * unitPrices;
    

    // total section display
    totalItem.textContent = totalItems + change;
    if(change === 1)
        totalCost.textContent = totalCosts + unitPrices;
    if(change === -1)
        totalCost.textContent = totalCosts - unitPrices;

    updateUnitInSession(change, id);    
}

function increaseQuantity(id) {
        changeQuantity(id, 1); // Call changeQuantity with +1
}

function updateUnitInSession(change, code) {
 
    $.ajax({
        url: 'data/manage_product_session.php', // The PHP script that handles the session update
        type: 'POST',
        data: { action: 'unit', code: code, change: change},
        success: function(response) {
            console.log('Cart updated:', response); // Log or handle response as needed
        },
        error: function(xhr, status, error) {
            console.error('Error updating cart:', error);
        }
    });
}

function updateNotesInSession(change, code) {
 
 $.ajax({
     url: 'data/manage_product_session.php', // The PHP script that handles the session update
     type: 'POST',
     data: { action: 'notes', code: code, change: change},
     success: function(response) {
         console.log('Cart updated:', response); // Log or handle response as needed
     },
     error: function(xhr, status, error) {
         console.error('Error updating cart:', error);
     }
 });
}
</script>