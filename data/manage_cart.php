<?php
session_start();
require 'Database.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productQuanity = 1; // default quantity

// collect all data form the post request
    if (isset($_POST['p_id']))
        $id = $_POST['p_id'];
    if (isset($_POST['p_size']))
        $productSize = $_POST['p_size'];
    if (isset($_POST['p_quanity']))
        $productQuanity = $_POST['p_quanity'];
    if (isset($_POST['p_title']))
        $productName = strtolower($_POST['p_title']);
    if (isset($_POST['p_unit_price']))
        $productPrice = $_POST['p_unit_price'];
    if (isset($_POST['p_image']))
        $productImage = $_POST['p_image'];

    // Check which button was clicked
    if (isset($_POST['submit'])) {
        $action = $_POST['submit'];
        
        if ($action === 'remove') {    
            // echo '<pre>';
            // print_r($action);
            // echo '</pre>';
            // exit();
            // Remove product from cart
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
            }
        }

        if ($action === 'buy' || $action === 'addtocard') {
            
            if (isset($_SESSION['cart'][$id])) {
                // Increment quantity if it exists
                $_SESSION['cart'][$id]['quantity'] = $_SESSION['cart'][$id]['quantity'] + $productQuanity ;
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
            }
        }

        if ($action === 'addtocard') {
            if (isset($_SERVER['HTTP_REFERER'])) 
                header("Location: " . $_SERVER['HTTP_REFERER']);
            else
                header("Location: ../shopping_cart.php"); 
        }else{
            header("Location: ../shopping_cart.php"); 
        } 
        exit();   
    }
    //....jquery call form add to cart icon
    $action = ''; 
    if (isset($_POST['action']))
        $action = $_POST['action'];

    if ($action === 'addtocard') {
        
        if (isset($_SESSION['cart'][$id])) {
            // Increment quantity if it exists
            $_SESSION['cart'][$id]['quantity'] = $_SESSION['cart'][$id]['quantity'] + $productQuanity ;
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
        }
    }

}

?>