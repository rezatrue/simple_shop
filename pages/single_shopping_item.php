
<style>
.textinput {
    width: 100%; /* Make textarea take full width of its parent */
    min-height: 75px; /* Set a minimum height */
    outline: none; /* Remove outline on focus */
    resize: none; /* Disable resizing if desired */
    border: 1px solid grey; /* Set border for textarea */
    box-sizing: border-box; /* Include padding and border in element's total width and height */
    padding: 5px; /* Add some padding inside the textarea */
}
</style>


<div class="container py-2">
    <div class="row">
    <form action="./data/manage_cart.php" method="POST" id="<?php echo $id; ?>">
        <div class="col-12">
            <div class="card position-relative">
                <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
                    <h5 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h5>
                    
                        <input type="hidden" name="p_id" value="<?php echo $id; ?>">
                        <button class="btn btn-danger btn-sm" type="submit" name="submit" value="remove">Delete</button>
                </div>
                <div class="row g-0">
                    <div class="col-md-3 d-flex justify-content-center align-items-center">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" style="width: 200px; height: 200px;" class="rounded-start" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    </div>
                    <div class="col-md-9 bg-light"> <!-- Light background for product details -->
                        <div class="card-body" data-product-id="<?php echo $id; ?>">
                            <p class="card-text"><strong>Total Price: </strong><strong id="total-price-<?php echo $id; ?>"><?php echo (float)htmlspecialchars($item['price']) * (int)htmlspecialchars($item['quantity']); ?></strong></p>
                            <ul class="list-inline  pb-3">
                                <li class="list-inline-item text-right">
                                <strong>Unit Price:</strong> <strong id="unit-price-<?php echo $id; ?>"><?php echo htmlspecialchars($item['price']); ?></strong> | <strong>Total Unit:</strong>
                                    <input type="hidden" name="product-quanity" id="product-quanity-<?php echo $id; ?>" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1">
                                </li>
                                <li class="list-inline-item"><span class="btn btn-success" id="btn-minus-<?php echo $id; ?>" onclick="changeQuantity(<?php echo $id; ?>, -1)">-</span></li>
                                <li class="list-inline-item"><span class="badge bg-secondary" id="var-value-<?php echo $id; ?>"><?php echo htmlspecialchars($item['quantity']); ?></span></li>
                                <li class="list-inline-item"><span class="btn btn-success" id="btn-plus-<?php echo $id; ?>" onclick="changeQuantity(<?php echo $id; ?>, 1)">+</span></li>
                            </ul>
                            <p class="card-text"><strong>Size: </strong><?php echo htmlspecialchars($item['size']); ?></p>
                            <input type="hidden" name="product-size" id="product-size-<?php echo $id; ?>" value="<?php echo htmlspecialchars($item['size']); ?>">
                            <p class="card-text">
                                <small class="card-text">
                                    <textarea class="textinput" id="notes-<?php echo $id; ?>" name="notes" rows="2" placeholder="Write what you are looking for..."><?php echo htmlspecialchars($item['notes']); ?></textarea><br>
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
                    <input type="hidden" name="action" id="action">
            </div>
        </div>
    </form>
    </div>
</div>


<script>
    document.getElementById('notes-<?php echo $id; ?>')
            .addEventListener('blur', function() {
                updateNotesInSession(
                    document.getElementById('notes-<?php echo $id; ?>').value.trim(), 
                    <?php echo $id; ?>);
            });
</script>