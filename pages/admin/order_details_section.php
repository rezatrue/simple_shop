<?php

require './data/manage_single_order.php'; 

// Create an instance of the Database class
$mso = new manageSingleOrder();

$items = 0;
$grand_total  = 0;

if (isset($_GET['o_id'])) 
    $o_id = $_GET['o_id'];

if(isset($_GET['o_id'])){
    $result = $mso->orderDetails($o_id);
    if (!empty($result['item'])) {
        foreach ($result['item'] as $row) {
            $items++;
            $grand_total = $grand_total + $row['p_price'] * $row['o_unit'];
        }
    } 
}    
    

?>

<!-- general form elements -->
<div class="card card-primary">
  <!-- <div class="card-header"> -->
    
  <div class="card-header d-flex justify-content-between align-items-center text-white">    
        <div class="name">
            <div class="input-group justify-content-between align-items-center">
                <strong >Order ID: </strong>
                <form action="order_list.php" method="GET">
                    <input type="text" class="form-control " id="o_id" name="o_id" placeholder="Order ID" value="<?php if(isset($_GET['o_id'])) echo $_GET['o_id'];?>" required>
                </form>
            </div>
        </div>
        <div class="name">
            <strong id="item-count" name="item-count"> Item: <?php echo $items; ?></strong>
        </div>
        <div class="name">
            <strong id="grand-total" name="grand-total" data-price="<?php echo $grand_total; ?>"> Total: <?php echo $grand_total; ?></strong>
        </div>
        <div class="name">
            <button class="btn btn-success btn-sm" type="submit" name="submit" value="remove">Close</button>
        </div>
    </div>
          
  <!-- </div> -->
  <!-- /.card-header -->
<!-- table start -->
    <div class="table-responsive">
       <table class="table table-striped table-hover w-auto">
          <thead class="table-light">
            <form action="" method="POST">
                <input type="hidden" id="o_id" name="o_id" value="<?php if(isset($_GET['o_id'])) echo $o_id; else echo 0; ?>">
                <input type="hidden" id="search-pid" name="search-pid" value="0">
            <tr>
                <td class="name justify-content-between align-items-center" >
                    <strong id="search-total">00</strong>
                </td>     
                <td class="name" colspan="2">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search-name" name="search-name" placeholder="Product name" >
                        <div id="productList" class="dropdown-menu"></div>
                    </div>    
                </td>
                <td class="name">
                    <div class="input-group">
                        <input type="number" class="form-control" id="search-unit" name="search-unit" placeholder="Product size" value="0">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" name="submit" value="add">ADD</button>
                        </div>
                    </div>
                </td>
                <td class="name">
                    <strong id="search-unit-price">1</strong>
                </td>
                <td class="name">
                    <input type="text" class="form-control col-xs-2" id="search-size" name="search-size" placeholder="Product size" value="">
                </td>
                <td class="name" colspan="2">
                    <input type="text" class="form-control" id="search-comment" name="search-comment" placeholder="Customer Comments" >
                </td>
            </tr>
            </form>
            <?php
                // Check if the form is submitted
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    
                    if (isset($_POST['search-name']) && isset($_POST['search-unit'])){
                        if (isset($_POST['o_id']))
                            $o_id = $_POST['o_id'];
                        if (isset($_POST['search-pid']))
                            $p_id = $_POST['search-pid'];
                        if (isset($_POST['search-unit']))
                            $o_unit = $_POST['search-unit'];
                        if (isset($_POST['search-size']))
                            $p_size = $_POST['search-size'];
                        if (isset($_POST['search-comment']))
                            $c_notes = $_POST['search-comment'];

                        // Call the function and store the result
                        $orderDetails = $mso->createNewOrderItem($o_id, $p_id, $o_unit, $p_size, $c_notes);
                    
                    }else return;
                    
                }
            ?>
            <tr class="bg-secondary">
                <td>Amount</td>    
                <td>Product</td> 
                <td>Name</td> 
                <td>Order Units</td> 
                <td>Price/Unit</td> 
                <td>Sizes</td> 
                <td>Customer Notes</td> 
                <td>NO/OFF</td> 
            </tr>
          </thead>

          <tbody>

                    <?php 
                        if (!empty($result['item'])) {
                            foreach ($result['item'] as $row) {
                                include('./pages/admin/order_details_single_row.php');
                            }
                        }
                    ?>
          </tbody>
       </table>
    </div>
<!-- table end-->
<script> 
function recalculateTotalNumbers(currentValue){
     alert(currentValue);
}

// calculation of total while adding new product
document.getElementById('search-unit')
            .addEventListener('keyup', function() {
                let unitText = document.getElementById('search-unit').value.trim();
                let priceText = document.getElementById('search-unit-price').innerText;
                let unitValue = parseFloat(unitText);
                let priceValue = parseFloat(priceText);
                let newProductPrice = unitValue * priceValue;
                document.getElementById('search-total').innerText = newProductPrice;

                let currentTotal = parseFloat(document.getElementById('grand-total').getAttribute('data-price'));
                let newTotal = currentTotal + newProductPrice;
                document.getElementById('grand-total').innerText = 'Total: ' + newTotal;

            });

// search product by name
$(document).ready(function() {
        // Load categories on input
        $('#search-name').on('input', function() {
            let query = $(this).val();
            if (query.length > 2) {
                $.ajax({
                    url: './data/fetch_products.php',
                    method: 'GET',
                    data: { query: query },
                    success: function(data) {
                        $('#productList').html(data).show();
                    }
                });
            } else {
                $('#productList').hide();
            }
        });

        // Select a category
        $(document).on('click', '.product-item', function() {
            let pname = $(this).text();
            let price = $(this).data('price');
            let sizes = $(this).data('sizes');
            let pid = $(this).data('pid');
            $('#search-pid').val(pid);
            $('#search-name').val(pname);
            $('#search-unit').val('0');
            $('#search-unit-price').text(price);
            $('#search-size').val(sizes);
            $('#productList').hide();
        });
    });
</script>   



