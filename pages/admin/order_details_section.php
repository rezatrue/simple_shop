<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    /* Custom styles for the modal */
    #cancelModal {
        display: none; /* Hide by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 20%; /* Could be more or less, depending on screen size */
    }
</style>

<?php

require './data/Database.php'; 

// Create an instance of the Database class

$items = 0;
$grand_total  = 0;

if (isset($_GET['o_id'])) 
    $o_id = $_GET['o_id'];

if(isset($_GET['o_id'])){
    $db = new Database();
    $result = $db->orderDetails($o_id);
    if (!empty($result['item'])) {
        foreach ($result['item'] as $row) {
            $items++;
            $grand_total = $grand_total + $row['p_price'] * $row['o_unit'];
        }
    } 
}    
    

?>
<!-- Modal for canceling order -->
<div id="cancelModal">
    <div class="modal-content">
        <h5>Reason for Canceling</h5>
        <textarea id="cancelReason" rows="4" class="form-control"></textarea>
        <div class="mt-3">
            <button id="submitCancel" class="btn btn-primary">Submit</button>
            <button id="closeModal" class="btn btn-secondary">Cancel</button>
        </div>
    </div>
</div>

<!-- general form elements -->
<div class="card card-primary">
  <!-- <div class="card-header"> -->
    
    <div class="card-header d-flex justify-content-between align-items-center text-white"> 
       
        <div class="name">
            <div class="input-group justify-content-between align-items-center">
                <strong >Order ID: </strong>
                <form action="./data/process_order.php" method="POST" id="orderForm">
                    <input type="text" class="form-control " id="o_id" name="o_id" placeholder="Order ID" value="<?php if(isset($_GET['o_id'])) echo $_GET['o_id'];?>" required>
            </div>
        </div>
        <div class="name">
            <strong id="item-count" name="item-count"> Item: <?php echo $items; ?></strong>
        </div>
        <div class="name">
            <strong id="grand-total" name="grand-total" data-price="<?php echo $grand_total; ?>"> Total: <?php echo $grand_total; ?></strong>
        </div>
        <div class="name">
            <input type="hidden" name="submit_other" value="other">
            <button class="btn btn-success btn-sm" type="submit" name="submit_confirm" value="confirm">Confirm Order</button>
        </div>
        <div class="name">
            <input type="hidden" name="reason" id="reasonInput">
            <button class="btn btn-danger btn-sm" type="submit" name="submit_cancel" value="cancel" id="cancelButton">Cancel Order</button>
            </form>
        </div>
        
    </div>
    
          
  <!-- </div> -->
  <!-- /.card-header -->
<!-- table start -->
    <div class="table-responsive">
       <table class="table table-striped table-hover w-auto">
          <thead class="table-light">
            <form action="./data/manage_single_order.php" method="POST">
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
                        <input type="number" class="form-control" id="search-unit" name="search-unit" placeholder="Product size" min="0" value="0" step=".001">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" name="submit" value="add">ADD</button>
                        </div>
                    </div>
                </td>
                <td class="name">
                    <strong id="search-unit-price">00</strong>
                </td>
                <td class="name">
                    <input type="text" class="form-control col-xs-2" id="search-size" name="search-size" placeholder="Product size" value="">
                </td>
                <td class="name" colspan="2">
                    <input type="text" class="form-control" id="search-comment" name="search-comment" placeholder="Customer Comments" >
                </td>
            </tr>
            </form>
           
            <tr class="bg-secondary">
                <td>Amount</td>    
                <td>Product</td> 
                <td>Name</td> 
                <td>Order Units</td> 
                <td>Price/Unit</td> 
                <td>Sizes</td> 
                <td>Customer Notes</td> 
                <td>ON/OFF</td> 
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
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>  
<script>
    let canSubmit = true;
    document.getElementById('cancelButton').onclick = function(event) {
        if(canSubmit){
            document.getElementById('cancelModal').style.display = 'block';
            event.preventDefault(); // Prevent default form submission
            canSubmit = false;
        }else{
            event.enableFormSubmission();
            canSubmit = true;
        }
        
    };

    document.getElementById('closeModal').onclick = function() {
        document.getElementById('cancelModal').style.display = 'none';
    };

    document.getElementById('submitCancel').onclick = function() {
        
        const reason = document.getElementById('cancelReason').value;
        if (reason.trim() !== '') {
            document.getElementById('reasonInput').value = reason;
            document.getElementById('cancelButton').click();// Submit the form
        } else {
            alert('Please provide a reason for canceling.');
        }
    };

    //Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === document.getElementById('cancelModal')) {
            document.getElementById('cancelModal').style.display = 'none';
        }
    };


document.getElementById('o_id').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Prevent default form submission
        // Set the hidden input to indicate that Enter was pressed
        document.querySelector('input[name="submit_other"]').value = 'other'; // Change button value
        document.getElementById('orderForm').submit(); // Submit the form
    }
});

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

        // Select a product
        $(document).on('click', '.product-item', function() {
            let pname = $(this).text();
            let price = $(this).data('price');
            let sizes = $(this).data('sizes');
            let pid = $(this).data('pid');
            $('#productList').hide();
            $('#search-unit').val('0');
            $('#search-pid').val(pid);
            $('#search-name').val(pname);
            $('#search-unit-price').text(price);
            $('#search-size').val(sizes);
            // Iterate through all elements with IDs starting with 'id-'
            $('[id^="id-"]').each(function() {
                var itemId = parseInt($(this).val()) || 0; 
                if(itemId == pid){
                    $('#search-pid').val('0');
                    $('#search-name').val('');
                    $('#search-unit-price').text('0');
                    $('#search-size').val('');
                    alert(pname +' is already in the list.')
                }
            });

        });
    });
</script>   



