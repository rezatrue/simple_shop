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
    <form action="./data/manage_cart.php" method="POST" id="form_001">
        <div class="col-12">
            <div class="card position-relative">
                <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
                    <h5 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h5>
                    
                        <input type="hidden" name="product-id" value="<?php echo $id; ?>">
                        <button class="btn btn-danger btn-sm" type="submit" name="submit" value="remove">Delete</button>
                </div>
                <div class="row g-0">
                    <div class="col-md-3 d-flex justify-content-center align-items-center">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" style="width: 200px; height: 200px;" class="rounded-start" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    </div>
                    <div class="col-md-9 bg-light"> <!-- Light background for product details -->
                        <div class="card-body" data-product-id="<?php echo $id; ?>">
                            <p class="card-text"><strong>Total Price: </strong><?php echo (float)htmlspecialchars($item['price']) * (int)htmlspecialchars($item['quantity']); ?></p>
                            <ul class="list-inline  pb-3">
                                <li class="list-inline-item text-right">
                                <strong>Unit Price:</strong> <?php echo htmlspecialchars($item['price']); ?> | <strong>Total Unit:</strong>
                                    <input type="hidden" name="product-quanity" id="product-quanity" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1">
                                </li>
                                <li class="list-inline-item"><span class="btn btn-success" id="btn-minus" onclick="changeQuantity(-1)">-</span></li>
                                <li class="list-inline-item"><span class="badge bg-secondary" id="var-value">1</span></li>
                                <li class="list-inline-item"><span class="btn btn-success" id="btn-plus" onclick="increaseQuantity()">+</span></li>
                            </ul>
                            <p class="card-text"><strong>Size: </strong><?php echo htmlspecialchars($item['size']); ?></p>
                            <p class="card-text">
                                <small class="card-text">
                                    <textarea class="textinput" id="notes" name="notes" rows="2" placeholder="Write what you are looking for..."><?php echo htmlspecialchars($item['notes']); ?></textarea><br>
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
    // Get the form and input fields
    const form = document.getElementById('form_001'); // <?php echo $id; ?>"
    const textarea = form.querySelector('#notes');
    const quantityHiddenInput = form.querySelector('#product-quanity');
    const quantityInput = form.querySelector('#var-value');
    const qval = quanity.value; 

    // Add blur event listener to the textarea
    textarea.addEventListener('blur', function() {
        // Check if the textarea has content
        if (textarea.value.trim() !== '') {
            alert("Hello    ......." + textarea.value.trim());
            document.getElementById('action').value = 'saveNotes';
            form.submit(); // Submit the form if there's content
        } else {
            alert("Please enter some notes before submitting."); // Optional: Alert for empty textarea
        }
    });

    function changeQuantity(newValue) {
        var valu = quantityHiddenInput.value;
        alert("Please enter some notes before submitting." + valu); 
    }

    function increaseQuantity() {
        // Get the span element by its ID
        //var quantityInput = document.getElementById('var-value');
        
        // Get the current value and convert it to a number
        var currentValue = parseInt(quantityInput.textContent, 10);
        
        // Increase the value
        quantityInput.textContent = currentValue + 1; // Increment by 1 or set to any desired value
    }
</script>