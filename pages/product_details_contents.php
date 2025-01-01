<?php
// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

if (isset($_GET['id']))
    $id = $_GET['id'];

$db = new Database();
$product  = $db->getProductDetails($id);
// Close the database connection
// echo '<pre>';
// print_r(sizeof($product));
// echo '</pre>';
// exit();
$db->close();
if(sizeof($product) == 0){
    echo "No Product Found. <a href='shop.php'>Go back</a>";
    exit();
}

?>

    <!-- Modal -->
    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="w-100 pt-1 mb-5 text-right">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="get" class="modal-content modal-body border-0 p-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="inputModalSearch" name="q" placeholder="Search ...">
                    <button type="submit" class="input-group-text bg-success text-light">
                        <i class="fa fa-fw fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>



    <!-- Open Content -->
    <section class="bg-light">
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-5 mt-5">
                    <?php
                        
                        $images = [];
                        if($product['p_images']){
                            foreach($product['p_images'] as $img){
                                if($img != null){
                                    $images[] = $img;
                                }
                            } 
                        }
                        include('product_images_carousel.php'); 

                        ?>
                </div>
                <!-- col end -->
                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="h2"><?php echo htmlspecialchars($product['p_name']); ?></h1>
                            <p class="h3 py-2"><?php echo htmlspecialchars($product['p_price']); ?></p>
                            <p class="py-2">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-secondary"></i>
                                <span class="list-inline-item text-dark">Rating 4.8 | 36 Comments</span>
                            </p>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Brand:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p class="text-muted"><strong>Easy Wear</strong></p>
                                </li>
                            </ul>

                            <h6>Description:</h6>
                            <p><?php echo htmlspecialchars_decode($product['p_short_description']); ?></p>
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Avaliable Color :</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p class="text-muted"><strong>White / Black</strong></p>
                                </li>
                            </ul>

                            <h6>Specification:</h6>
                            <p><?php echo $product['p_specification']; ?></p>

                            <form action="./data/manage_cart.php" method="POST">
                                <input type="hidden" name="p_id" value="<?php echo $product['p_id']; ?>">
                                <input type="hidden" name="p_title" value="<?php echo htmlspecialchars($product['p_name']); ?>">
                                <input type="hidden" name="p_unit_price" value="<?php echo $product['p_price']; ?>">
                                <input type="hidden" name="p_image" value="<?php echo $product['p_images'][0]; ?>">
                                <div class="row">
                                    
                                    <div class="col-auto">
                                        <ul class="list-inline pb-3">
                                            <li class="list-inline-item">Size :
                                                <input type="hidden" name="p_size" id="product-size" value="S">
                                            </li>
                                            <?php 
                                                $sizes = explode(";",trim($product['p_sizes']));
                                                foreach($sizes as $size){
                                                    echo '<li class="list-inline-item"><span class="btn btn-success btn-size">'. $size .'</span></li>';
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="col-auto">
                                        <ul class="list-inline pb-3">
                                            <li class="list-inline-item text-right">
                                                Quantity
                                                <input type="hidden" name="p_quanity" id="product-quanity" value="1">
                                            </li>
                                            <li class="list-inline-item"><span class="btn btn-success" id="btn-minus">-</span></li>
                                            <li class="list-inline-item"><span class="badge bg-secondary" id="var-value">1</span></li>
                                            <li class="list-inline-item"><span class="btn btn-success" id="btn-plus">+</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row pb-3">
                                    <div class="col d-grid">
                                        <button type="submit" class="btn btn-success btn-lg" name="submit" value="buy">Buy</button>
                                        </form>
                                    </div>
                            
                                    <div class="col d-grid">
                                        <button class="btn btn-success btn-lg"  name="submit" value="addtocard">Add To Cart</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Close Content -->

    <!-- Start Article -->
    <section class="py-5">
        <div class="container">
            <div class="row text-left p-2 pb-3">
                <h4>Related Products</h4>
            </div>

            <!--Start Carousel Wrapper-->
            <div id="carousel-related-product">

                <?php 
                $db = new Database();
                $idList = [];
                $relatedProductDetails = [];
                if (!empty($product['categories'])) {
                    foreach ($product['categories'] as $category) {
                        // product_categories BD (cat_id) -> p_id
                        $relatedProductList = $db->queryForRelatedProduct('visitor', $category['sub_cat_id'], "1", "25");
                        if (!empty($relatedProductList['product'])) {
                            foreach ($relatedProductList['product'] as $product) {
                                if (!empty($relatedProductList['product'])) {
                                    if (!in_array($product['p_id'], $idList)) {
                                        array_push($idList, $product['p_id']);
                                        array_push($relatedProductDetails, $product);
                                    }
                                }
                            }
                        }
                    }    
                } else {
                    echo "No categories found.";
                }
                $db->close();
                // Check if there are results and fetch data
                if (!empty($relatedProductDetails)) {
                    foreach ($relatedProductDetails as $row) {
                        include('related_product_card.php');
                    }
                } else {
                    echo "0 results";
                }
                
                ?>

            </div>

        </div>
    </section>
    <!-- End Article -->
<script src="assets/js/jquery-1.11.0.min.js"></script>
<script>

<?php     
if (!empty($relatedProductDetails)) {
    foreach ($relatedProductDetails as $row) {
        $image = '';
        if(!empty($row['p_images'])){ foreach($row['p_images'] as $img){if($img){ $image = json_encode($img) ; break;}} } else { $image = '""'; }
        echo "
         $(document).ready(function() {
                // Load categories on input
                $('#submit-". $row['p_id']."').on('click', function() {
                    let action = $(this).val();
                    //alert(action);
                    let p_id = ". $row['p_id'].";
                    let p_name = '". $row['p_name']."';
                    let p_price = ". $row['p_price'].";
                    let p_quanity = 1;
                    let p_sizes = '". htmlspecialchars($row['p_sizes'])."';
                    let p_image = ".$image.";
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
        ";
    }
} 
?>   
</script> 

