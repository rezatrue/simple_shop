<div class="card mb-3">
    <img class="card-img img-fluid" 
        src="<?php 
            if(sizeof($images) > 0) echo $images[0];
            else echo 'assets/img/cat/dumy.png'; ?>" alt="Card image cap" id="product-detail">
</div>
<div class="row">
    <!--Start Controls-->
    <div class="col-1 align-self-center">
        <a href="#multi-item-example" role="button" data-bs-slide="prev">
            <i class="text-dark fas fa-chevron-left"></i>
            <span class="sr-only">Previous</span>
        </a>
    </div>
    <!--End Controls-->
    <!--Start Carousel Wrapper-->
    <div id="multi-item-example" class="col-10 carousel slide carousel-multi-item" data-bs-ride="carousel">
        <!--Start Slides-->
        <div class="carousel-inner product-links-wap" role="listbox">
            <?php
                $fast = 0;
                $second = 0;
                $third = 0; 
                if($num = sizeof($images) > 0) {
                    if($num > 2){
                        $fast = 3;
                        $num =  $num - 3;
                    }else{
                        $fast = $num;
                        $num = 0;
                    }
                    if($num > 2){
                        $second = 3;
                        $num =  $num - 3;
                    }else{
                        $second = $num;
                        $num = 0;
                    }
                    if($num > 0){
                        $third = $num;
                        $num = 0;
                    }
                }


                


                ?>
            <!--First slide-->
            <div class="carousel-item active">
                <div class="row">
                    <div class="col-4">
                        <a href="#">
                            <img class="card-img img-fluid" src="assets/img/cat/dumy.png" alt="Product Image 1">
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <img class="card-img img-fluid" src="assets/img/cat/dumy.png" alt="Product Image 2">
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <img class="card-img img-fluid" src="assets/img/cat/dumy.png" alt="Product Image 3">
                        </a>
                    </div>
                </div>
            </div>
            <!--/.First slide-->

            <!--Second slide-->
            <div class="carousel-item">
                <div class="row">
                    <div class="col-4">
                        <a href="#">
                            <img class="card-img img-fluid" src="assets/img/cat/dumy.png" alt="Product Image 4">
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <img class="card-img img-fluid" src="assets/img/cat/dumy.png" alt="Product Image 5">
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <img class="card-img img-fluid" src="assets/img/cat/dumy.png" alt="Product Image 6">
                        </a>
                    </div>
                </div>
            </div>
            <!--/.Second slide-->

            <!--Third slide-->
            <div class="carousel-item">
                <div class="row">
                    <div class="col-4">
                        <a href="#">
                            <img class="card-img img-fluid" src="assets/img/cat/dumy.png" alt="Product Image 7">
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <img class="card-img img-fluid" src="assets/img/cat/dumy.png" alt="Product Image 8">
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="#">
                            <img class="card-img img-fluid" src="assets/img/cat/dumy.png" alt="Product Image 9">
                        </a>
                    </div>
                </div>
            </div>
            <!--/.Third slide-->
        </div>
        <!--End Slides-->
    </div>
    <!--End Carousel Wrapper-->
    <!--Start Controls-->
    <div class="col-1 align-self-center">
        <a href="#multi-item-example" role="button" data-bs-slide="next">
            <i class="text-dark fas fa-chevron-right"></i>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <!--End Controls-->
</div>