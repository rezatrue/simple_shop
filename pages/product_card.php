<div class="col-md-4">
    <div class="card mb-4 product-wap rounded-0">
        <div class="card rounded-0">
            <img class="card-img rounded-0 img-fluid" src="<?php
                                                            $noimage = true;
                                                            if($row['p_images']){
                                                            foreach($row['p_images'] as $img){
                                                                if($img != null){
                                                                        echo $img;
                                                                        $noimage = false;
                                                                        break;}
                                                                }
                                                            }
                                                            if($noimage) echo 'assets/img/prod/dumy.png';?>" alt="<?php echo htmlspecialchars($row['p_name']); ?>">
            <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                <ul class="list-unstyled">
                    <!-- <li><a class="btn btn-success text-white" href="product_details.php?id=<?php echo htmlspecialchars($row['p_id']); ?>"><i class="far fa-heart"></i></a></li> -->
                    <li><a class="btn btn-success text-white mt-2" href="product_details.php?id=<?php echo htmlspecialchars($row['p_id']); ?>"><i class="far fa-eye"></i></a></li>
                    <button type="submit" id="submit-<?php echo $row['p_id']; ?>" name="submit" value="addtocard" class="btn btn-success text-white mt-2"><i class="fas fa-cart-plus"></i></button>  
                </ul>
            </div>
        </div>
        <div class="card-body">
            <a href="product_details.php?id=<?php echo htmlspecialchars($row['p_id']); ?>" class="h3 text-decoration-none"><?php echo htmlspecialchars($row['p_name']); ?></a>
            <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                <li><?php echo htmlspecialchars($row['p_sizes']); ?></li>
                <li class="pt-2">
                    <span class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                    <span class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                    <span class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                    <span class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                    <span class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                </li>
            </ul>
            <ul class="list-unstyled d-flex justify-content-center mb-1">
                <li>
                    <i class="text-warning fa fa-star"></i>
                    <i class="text-warning fa fa-star"></i>
                    <i class="text-warning fa fa-star"></i>
                    <i class="text-muted fa fa-star"></i>
                    <i class="text-muted fa fa-star"></i>
                </li>
            </ul>
            <p class="text-center mb-0"><?php echo htmlspecialchars($row['p_price']); ?></p>
        </div>
    </div>
</div>
<script>
 $(document).ready(function() {
    // Load categories on input
    $('#submit-<?php echo $row['p_id']; ?>').on('click', function() {
        let action = $(this).val();
        //alert(action);
        let p_id = <?php echo $row['p_id']; ?>;
        let p_name = '<?php echo $row['p_name']; ?>';
        let p_price = <?php echo $row['p_price']; ?>;
        let p_quanity = 1;
        let p_sizes = '<?php echo htmlspecialchars($row['p_sizes']); ?>';
        let p_image = <?php if(!empty($row['p_images'])){ foreach($row['p_images'] as $img){if($img){ echo json_encode($img) ; break;}} } else { echo '""'; }?>;
        $.ajax({
            url: './data/manage_cart.php',
            method: 'POST',
            data: { action: action, p_id: p_id, p_title: p_name, p_unit_price: p_price, p_quanity: p_quanity, p_size: p_sizes, p_image: p_image},
            success: function(data) {
                console.log('success');
                }
        });
        
    });
});   
</script>