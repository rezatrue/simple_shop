<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// Initialize cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if action is set
    if (isset($_POST['action'])) {
        $action = $_POST['action']; // when unit
        $id = $_POST['code']; // product id
        $val = $_POST['change']; // value to be added +1/-1

        if ($action === 'unit') {
            // Add item to cart
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] = $_SESSION['cart'][$id]['quantity'] + $val; 
            }
        }

        if ($action === 'notes') {
            // Add item to cart
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['notes'] = $val; 
            }
        }

        // Optionally return updated cart or success message
        echo json_encode($_SESSION['cart'][$id]);

    }
}
?>