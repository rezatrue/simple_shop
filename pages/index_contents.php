<?php
// Include the Database class
require './data/Database.php'; // Adjust the path as necessary   
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

    <!-- Start Banner Hero -->
    <div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="1"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="container">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img class="img-fluid" src="./assets/img/banner_img_01.jpg" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left align-self-center">
                                <h1 class="h1 text-success"><b>Jewellery</b> collection</h1>
                                <h3 class="h2">Elegant and Timeless Jewelry Collection</h3>
                                <p>
                                    Welcome to Luxe Jewels, your premier destination for exquisite jewelry that embodies elegance and sophistication. Our carefully curated collection features stunning pieces crafted from the finest materials, designed to enhance your beauty and style. Whether you're looking for the perfect engagement ring, a statement necklace, or delicate earrings, Luxe Jewels offers something special for every occasion..
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img class="img-fluid" src="./assets/img/banner_img_02.jpg" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left">
                                <h1 class="h1 text-success"><b>Shari</b> collection</h1>
                                <h3 class="h2">Stylish and Versatile Women's Dress</h3>
                                <p>
                                The Shari is a contemporary women’s dress that combines elegance with comfort, making it perfect for various occasions. This dress features an A-line silhouette that flatters all body types, ensuring a graceful fit. With options like a crossover V-neckline and tiered skirts, the Shari brings a playful yet sophisticated vibe to any wardrobe.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img class="img-fluid" src="./assets/img/banner_img_03.jpg" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left">
                                <h1 class="h1 text-success"><b>Kids Toy</b> Collection</h1>
                                <h3 class="h2">Engaging and Educational Toys for Children </h3>
                                <p>
                                Welcome to Playtime Paradise, where imagination meets learning! Our extensive collection of children's toys is designed to inspire creativity and promote developmental skills in kids of all ages. From educational puzzles and building blocks to interactive games and plush toys, we offer a wide variety of options that cater to every child's interests and developmental needs.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="prev">
            <i class="fas fa-chevron-left"></i>
        </a>
        <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="next">
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>
    <!-- End Banner Hero -->

    <!-- Start Categories of The Month -->
    <section class="container py-5">
        <div class="row text-center pt-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Categories of The Month</h1>
                <p>
                    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
                    deserunt mollit anim id est laborum.
                </p>
            </div>
        </div>
        <div class="row">
            <?php 
                // Create an instance of the Database class
                $db = new Database();
                $result = $db->queryIsCatOfMonth();
                // Check if there are results and fetch data
                if ($result && $result->num_rows > 0) {
                    // Fetch all results as an associative array
                    $data = $db->fetchAll($result);
                    // Iterate through each row using foreach loop
                    foreach ($data as $row) {
                        include('category_of_month_card.php'); 
                    }
                } else {
                    echo "0 results";
                }
                
                // Close the database connection
                $db->close();
            ?>
        </div>
    </section>
    <!-- End Categories of The Month -->

    <!-- Start Featured Product -->
    <section class="bg-light">
        <div class="container py-5">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">Featured Product</h1>
                    <p>
                        Reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        Excepteur sint occaecat cupidatat non proident.
                    </p>
                </div>
            </div>
            <div class="row">
            <?php 
                    // Create an instance of the Database class
                    $db = new Database();
                    $result = $db->queryFeaturedList();

                    // Check if there are results and fetch data
                    if ($result && $result->num_rows > 0) {
                        // Fetch all results as an associative array
                        $data = $db->fetchAll($result);
                        // Iterate through each row using foreach loop
                        foreach ($data as $row) {
                            include('featured_product_card.php');
                            //echo "ID: " . $row["p_id"] . " - Name: " . $row["p_name"] . " " . $row["p_price"] . " " . $row["p_image"] . "<br>";
                        }
                    } else {
                        echo "0 results";
                    }
                    
                    // Close the database connection
                    $db->close();
                    ?>
            </div>
        </div>
    </section>
    <!-- End Featured Product -->


