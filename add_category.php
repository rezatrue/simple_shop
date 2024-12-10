<?php
    if(!isset($_SESSION)) 
        session_start(); 
    
    if (!isset($_SESSION['user']) || !$_SESSION['user']) {
        // Redirect to the login page
        header('Location: admin.php'); 
        exit(); 
    }
    $page_content = './pages/admin/add_category_section.php';
    include ('./layouts/admin/main.php'); 
?>