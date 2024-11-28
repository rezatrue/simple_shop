<div class="col-md-4">
    <div class="card mb-4 product-wap rounded-0">
        <div class="card rounded-0">
            <img class="card-img rounded-0 img-fluid" src="<?php echo htmlspecialchars($row['p_image']); ?>" alt="<?php echo htmlspecialchars($row['p_name']); ?>">
            <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                <ul class="list-unstyled">
                    <li><a class="btn btn-success text-white" href="shop_single.php?id=<?php echo htmlspecialchars($row['p_id']); ?>"><i class="far fa-heart"></i></a></li>
                    <li><a class="btn btn-success text-white mt-2" href="shop_single.php?id=<?php echo htmlspecialchars($row['p_id']); ?>"><i class="far fa-eye"></i></a></li>
                    <li><a class="btn btn-success text-white mt-2" href="shop_single.php?id=<?php echo htmlspecialchars($row['p_id']); ?>"><i class="fas fa-cart-plus"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <a href="shop_single.php?id=<?php echo htmlspecialchars($row['p_id']); ?>" class="h3 text-decoration-none"><?php echo htmlspecialchars($row['p_name']); ?></a>
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