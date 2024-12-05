<style>
    .page-item.active .page-link{
    background-color: #69bb7e;
    }
</style>    

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


    <!-- Start Content -->
    <div class="container py-5">
        <div class="row">
<!--  side bar starts here -->
            <div class="col-lg-3">
            <h1 class="h5 pb-2">For more go to Categories</h1>
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
                                <a class="h2 text-dark text-decoration-none mr-3" href="#"><strong>Thank you :</strong></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 pb-4">
                        <div class="d-flex">
                            <select class="form-control">
                                <option>Search by name</option>
                            </select>
                        </div>
                    </div>
                </div>
            <!-- product carts starts -->    
                <div class="row">            
                    <p>Your order is now placed to our system.</p>
                    <p>Please do contact to 01700000000 for delivery details. <br>
                        [as we don't keep any of your personal infomation to our system]</p>
                    <p>Please do mention your order number <?php echo htmlspecialchars($_GET['order_id']); ?>.</p>
                    <p>Go back for Shipping -> <a href='shop.php'>Click here</p>
                </div>
            <!-- product carts ebds -->     

            </div>

        </div>
    </div>
    <!-- End Content -->

<!-- End Modal -->
