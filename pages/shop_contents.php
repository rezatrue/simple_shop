<style>
    .page-item.active .page-link{
    background-color: #69bb7e;
    }
</style>    

<?php
// Include the Database class
require './data/Database.php'; // Adjust the path as necessary

$page = 1;
if (isset($_GET['page'])) 
    $page = $_GET['page'];

if (isset($_GET['cat'])) 
    $cat_id = $_GET['cat'];  

$totalItems = 0; // Total number of items
$itemsPerPage = 6; // Items per page    

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


    <!-- Start Content -->
    <div class="container py-5">
        <div class="row">
<!--  side bar starts here -->
            <div class="col-lg-3">
            <h1 class="h2 pb-4">Categories</h1>
                <ul class="list-unstyled templatemo-accordion">
                    <?php
                        $db = new Database();
                        $data = $db->getCategoryList(); 
                        $db->close();
                        if (!empty($data)){
                            $lastId = 0;
                            $cat_subcat = [];
                            $subcats = [];
                            foreach ($data as $row) {
                                if ($lastId == 0){
                                    $lastId = $row['cat_id'];
                                    $cat_subcat[] =  ['cat_name'=>$row['cat_name']];
                                    $subcats[] = ['sub_cat_name'=>$row['sub_cat_name'], 'sub_cat_id'=>$row['sub_cat_id']] ;
                                }
                                elseif ($lastId == $row['cat_id']){
                                    $subcats[] = ['sub_cat_name'=>$row['sub_cat_name'], 'sub_cat_id'=>$row['sub_cat_id']] ;
                                }     
                                elseif ($lastId != $row['cat_id']){
                                    $cat_subcat[] =  ['subcats'=>$subcats ];
                                    include('single_category_menu.php');

                                    $cat_subcat = [];
                                    $subcats = [];
                                    $lastId = $row['cat_id'];
                                    $cat_subcat[] =  ['cat_name'=>$row['cat_name']];
                                    $subcats[] = ['sub_cat_name'=>$row['sub_cat_name'], 'sub_cat_id'=>$row['sub_cat_id']] ;
                                }
                                
                            }
                            $cat_subcat[] =  ['subcats'=>$subcats ];
                            include('single_category_menu.php');
                        } 
                    ?>
                </ul>    
            </div>
<!--  side bar ends here -->
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-inline shop-top-menu pb-3 pt-1">
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none mr-3" href="shop.php">All</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none mr-3" href="?cat=30">Men's</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="h3 text-dark text-decoration-none" href="?cat=38">Women's</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 pb-4">
                        <div class="d-flex">
                            <select class="form-control">
                                <option>Featured</option>
                                <option>A to Z</option>
                                <option>Item</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php 
                    // Create an instance of the Database class
                    $db = new Database();

                    if(isset($_GET['cat'])){
                        if($db->isMainCat($cat_id) === 1){
                            $result = $db->queryAllSubcatProductsForCat($cat_id, $page, $itemsPerPage);
                            $totalItems = $db->productCountForMainCat($cat_id);
                        }else{
                            $result = $db->queryForRelatedProduct('visitor', $cat_id, $page, $itemsPerPage);
                            $totalItems = $db->productCountForCat('visitor', $cat_id); 
                        }                       
                    }else{
                        $result = $db->queryForListPage('visitor', $page, $itemsPerPage);
                        $totalItems = $db->queryCountForListPage('visitor');
                    }

                    if (!empty($result['product'])) {
                        foreach ($result['product'] as $row) {
                            include('product_card.php');
                        }
                    } else {
                        echo "0 results";
                    }
                    
                    // Close the database connection
                    $db->close();
                    ?>
                </div>
                <!-- pagiantion start -->
                <?php
                    // Sample data (replace with your actual data source)

                    $totalPages = ceil($totalItems / $itemsPerPage); // Total number of pages

                    // Get the current page from the URL, default to 1 if not set
                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                    // Ensure current page is within valid range
                    if ($currentPage < 1) {
                        $currentPage = 1;
                    } elseif ($currentPage > $totalPages) {
                        $currentPage = $totalPages;
                    }

                    // Calculate the offset for the SQL query (if applicable)
                    $offset = ($currentPage - 1) * $itemsPerPage;

                ?>
                <div class="row">
                    <ul class="pagination pagination-lg justify-content-end">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link active rounded-0 mr-3 shadow-sm" 
                                    href="<?php if(isset($_GET['cat'])) echo '?cat=' . $cat_id . '&page=' . $currentPage - 1; else echo '?page=' . $currentPage - 1; ?>">Previous</a>
                            </li>
                        <?php endif; ?>

                        <?php
                        // Display first page
                        if ($totalPages > 1) {
                            echo '<li class="page-item ' . ($currentPage === 1 ? 'active' : '') . '">';
                            echo '<a class="page-link rounded-0 mr-3 shadow-sm" 
                            href="'. ((isset($_GET['cat'])) ? '?cat=' . $cat_id . '&page=1' : '?page=1') .'">1</a>';
                            echo '</li>';
                        }

                        // Display ellipsis if needed
                        if ($totalPages > 4 && $currentPage > 3) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }

                        // Display pages around current page
                        for ($i = max(2, $currentPage - 1); $i <= min($totalPages - 1, $currentPage + 1); $i++) {
                            echo '<li class="page-item ' . ($currentPage === $i ? 'active' : '') . '">';
                            echo '<a class="page-link rounded-0 mr-3 shadow-sm"
                                href="'. ((isset($_GET['cat'])) ? '?cat=' . $cat_id . '&page=' : '?page=') . $i . '">' . $i . '</a>';
                            echo '</li>';
                        }

                        // Display last page if needed
                        if ($totalPages > 3 && $currentPage < $totalPages - 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                            echo '<li class="page-item ' . ($currentPage === $totalPages ? 'active' : '') . '">';
                            echo '<a class="page-link rounded-0 mr-3 shadow-sm" 
                                href="'. ((isset($_GET['cat'])) ? '?cat=' . $cat_id . '&page=' : '?page=') . $totalPages . '">' . $totalPages . '</a>';
                            echo '</li>';
                        }

                        if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link rounded-0 mr-3 shadow-sm"
                                    href="<?php if(isset($_GET['cat'])) echo '?cat=' . $cat_id . '&page=' . $currentPage + 1; else echo '?page=' . $currentPage + 1; ?>" >Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- pagiantion ends -->
            </div>

        </div>
    </div>
    <!-- End Content -->

    <!-- Start Brands -->
    <section class="bg-light py-5">
        <div class="container my-4">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">Our Brands</h1>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        Lorem ipsum dolor sit amet.
                    </p>
                </div>
                <div class="col-lg-9 m-auto tempaltemo-carousel">
                    <div class="row d-flex flex-row">
                        <!--Controls-->
                        <div class="col-1 align-self-center">
                            <a class="h1" href="#multi-item-example" role="button" data-bs-slide="prev">
                                <i class="text-light fas fa-chevron-left"></i>
                            </a>
                        </div>
                        <!--End Controls-->

                        <!--Carousel Wrapper-->
                        <div class="col">
                            <div class="carousel slide carousel-multi-item pt-2 pt-md-0" id="multi-item-example" data-bs-ride="carousel">
                                <!--Slides-->
                                <div class="carousel-inner product-links-wap" role="listbox">

                                    <!--First slide-->
                                    <div class="carousel-item active">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End First slide-->

                                    <!--Second slide-->
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Second slide-->

                                    <!--Third slide-->
                                    <div class="carousel-item">
                                        <div class="row">
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_01.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_02.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_03.png" alt="Brand Logo"></a>
                                            </div>
                                            <div class="col-3 p-md-5">
                                                <a href="#"><img class="img-fluid brand-img" src="assets/img/brand_04.png" alt="Brand Logo"></a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Third slide-->

                                </div>
                                <!--End Slides-->
                            </div>
                        </div>
                        <!--End Carousel Wrapper-->

                        <!--Controls-->
                        <div class="col-1 align-self-center">
                            <a class="h1" href="#multi-item-example" role="button" data-bs-slide="next">
                                <i class="text-light fas fa-chevron-right"></i>
                            </a>
                        </div>
                        <!--End Controls-->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Brands-->

<!-- End Modal -->
