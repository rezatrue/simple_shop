<div class="col-12 col-md-4 mb-4">
                    <div class="card h-100">
                        <a href="shop_single.php?id=<?php echo htmlspecialchars($row['p_id']); ?>">
                            <img src="<?php echo htmlspecialchars($row['p_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['p_name']); ?>">
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
                            <a href="shop_single.php?id=<?php echo htmlspecialchars($row['p_id']); ?>" class="h2 text-decoration-none text-dark"><?php echo htmlspecialchars($row['p_name']); ?></a>
                            <p class="card-text">
                                <?php echo htmlspecialchars($row['p_short_description']); ?>
                            </p>
                            <p class="text-muted">Reviews (24)</p>
                        </div>
                    </div>
                </div>