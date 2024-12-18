<div class="col-12 col-md-4 p-5 mt-3">
    <a href="shop.php?cat=<?php echo htmlspecialchars($row['cat_id']); ?>"><img src="<?php echo htmlspecialchars($row['cat_image']); ?>" class="rounded-circle img-fluid border"></a>
    <h5 class="text-center mt-3 mb-3" style="text-transform: capitalize;"><?php echo htmlspecialchars($row['cat_name']); ?></h5>
    <p class="text-center"><a class="btn btn-success" href="shop.php?cat=<?php echo htmlspecialchars($row['cat_id']); ?>">Go Shop</a></p>
</div>