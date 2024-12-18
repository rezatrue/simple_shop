<div class="p-2 pb-3">
    <div class="product-wap card rounded-0">
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
                                                            if($noimage) echo 'assets/img/prod/dumy.png';?>">
            <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                <ul class="list-unstyled">
                    <li><a class="btn btn-success text-white" href="product_details.php?id=<?php echo htmlspecialchars($row['p_id']); ?>"><i class="far fa-heart"></i></a></li>
                    <li><a class="btn btn-success text-white mt-2" href="product_details.php?id=<?php echo htmlspecialchars($row['p_id']); ?>"><i class="far fa-eye"></i></a></li>
                    <form action="./data/manage_cart.php" method="POST">
                        <input type="hidden" name="product-id" value="<?php echo $row['p_id']; ?>">
                        <input type="hidden" name="product-title" value="<?php echo $row['p_name']; ?>">
                        <input type="hidden" name="product-unit-price" value="<?php echo $row['p_price']; ?>">
                        <input type="hidden" name="product-quanity" value="1">
                        <input type="hidden" name="product-size" value="<?php echo htmlspecialchars($row['p_sizes']); ?>">
                        <input type="hidden" name="product-image" value="<?php echo htmlspecialchars($row['p_images'][0]); ?>">
                        <button type="submit" name="submit" value="addtocard" class="btn btn-success text-white mt-2"><i class="fas fa-cart-plus"></i></button>
                    </form>
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
                    <i class="text-warning fa fa-star"></i>
                    <i class="text-muted fa fa-star"></i>
                </li>
            </ul>
            <p class="text-center mb-0"><?php echo htmlspecialchars($row['p_price']); ?></p>
        </div>
    </div>
</div>