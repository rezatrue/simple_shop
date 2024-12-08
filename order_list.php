<?php
    if(!isset($_SESSION)) 
        session_start(); 

    if (!isset($_SESSION['user'])) {
        // Redirect to the login page
        header('Location: admin.php'); 
        exit(); 
    }
    $page_content = './pages/admin/order_list_section.php';
    include ('./layouts/admin/admin_main.php'); 
?>