<style>
 .multiline-truncate {
    display: -webkit-box; /* Use flexbox model */
    -webkit-box-orient: vertical; /* Set the box orientation */
    -webkit-line-clamp: 3; /* Limit to 3 lines */
    overflow: hidden; /* Hide overflow */
    text-overflow: ellipsis; /* Add ellipsis */
}   
</style>
<div class="col-12 col-md-4 mb-4">
    <div class="card h-100">
        <a href="product_details.php?id=<?php echo htmlspecialchars($row['p_id']); ?>">
            <img src="<?php
                        $noimage = true;
                        if($row['p_images']){
                        foreach($row['p_images'] as $img){
                            if($img != null){
                                    echo $img;
                                    $noimage = false;
                                    break;}
                            }
                        }
                        if($noimage) echo 'assets/img/prod/dumy.png';?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['p_name']); ?>">
        </a>
        <div class="card-body">
            <ul class="list-unstyled d-flex justify-content-between">
                <li>
                    <i class="text-warning fa fa-star"></i>
                    <i class="text-warning fa fa-star"></i>
                    <i class="text-warning fa fa-star"></i>
                    <i class="text-muted fa fa-star"></i>
                    <i class="text-muted fa fa-star"></i>
                </li>
                <li class="text-muted text-right">$240.00</li>
            </ul>
            <a href="product_details.php?id=<?php echo htmlspecialchars($row['p_id']); ?>" class="h2 text-decoration-none text-dark"><?php echo htmlspecialchars($row['p_name']); ?></a>
            <p class="card-text multiline-truncate">
                <?php echo htmlspecialchars($row['p_short_description']); ?>
            </p>
            <p class="text-muted">Reviews (24)</p>
        </div>
    </div>
</div>