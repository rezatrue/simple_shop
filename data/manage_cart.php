<?php
session_start();
require 'Database.php'; 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $message = null; // if not null we will send it via session
    $productQuanity = 1; // default quantity

// collect all data form the post request
    if (isset($_POST['product-id']))
        $id = $_POST['product-id'];
    if (isset($_POST['product-size']))
        $productSize = $_POST['product-size'];
    if (isset($_POST['product-quanity']))
        $productQuanity = $_POST['product-quanity'];
    if (isset($_POST['product-title']))
        $productName = strtolower($_POST['product-title']);
    if (isset($_POST['product-unit-price']))
        $productPrice = $_POST['product-unit-price'];
    if (isset($_POST['product-image']))
        $productImage = $_POST['product-image'];


    // Check which button was clicked
    if (isset($_POST['submit'])) {
        $action = $_POST['submit'];

        if ($action === 'remove') {    
              
            // Remove product from cart
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
                echo " - removed ";
            }
        }

        if ($action === 'buy' || $action === 'addtocard') {
            echo "Buy || addtocard button clicked.";
            if (isset($_SESSION['cart'][$id])) {
                // Increment quantity if it exists
                $_SESSION['cart'][$id]['quantity'] = $_SESSION['cart'][$id]['quantity'] + $productQuanity ;
                echo " - number Increased.";
            } else {
                // Add new product with quantity 1
                $_SESSION['cart'][$id] = [
                    'name' => $productName,
                    'price' => $productPrice,
                    'quantity' => $productQuanity,
                    'size' => $productSize,
                    'image' => $productImage,
                    'notes' => '',
                ];
                echo " - Added.";
            }
        } 
    } else {
        echo "No action specified.";
    }

    header("Location: ../shopping_cart.php");
    exit();

}

?>