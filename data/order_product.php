<?php

require 'Database.php'; 

session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $message = null; // if not null we will send it via session

// collect all data form the post request
    if (isset($_POST['product-id']))
        $productId = $_POST['product-id'];
    if (isset($_POST['product-title']))
        $productName = strtolower($_POST['product-title']);
    if (isset($_POST['product-unit-price']))
        $productPrice = $_POST['product-unit-price'];
    if (isset($_POST['product-size']))
        $productSize = $_POST['product-size'];
    if (isset($_POST['product-quanity']))
        $productQuanity = $_POST['product-quanity'];

    // Check which button was clicked
    if (isset($_POST['submit'])) {
        $action = $_POST['submit'];

        if ($action === 'buy') {
            // Code to handle the "Buy" action
            echo "Buy button clicked.";
            // Add your processing logic here
            
        } 
        if ($action === 'addtocard') {
            // Code to handle the "Add To Cart" action
            echo "Add To Cart button clicked.";
            // Add your processing logic here
        }
    } else {
        echo "No action specified.";
    }

    echo "<br>" .  $productId;       
    echo "<br>" .  $productName;       
    echo "<br>" .  $productPrice;       
    echo "<br>" .  $productSize;       
    echo "<br>" .  $productQuanity;    

}

?>