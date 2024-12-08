<?php
ob_start();
if(!isset($_SESSION)) 
    session_start(); // Start the session

    if (isset($_GET['user'])) {
        $user = $_GET['user'];
        if($user === 'logout'){
            unset($_SESSION['user']); 
            //session_destroy();
        }
    }

    // echo '<pre>';
    // print_r($_SESSION['user']);
    // echo '</pre>';
    // exit();

    if (isset($_SESSION['user'])) {
        header('Location: order_list.php');
        exit(); 
    }else{
        // Redirect to the login page
        $page_content = './pages/admin/login_registration_section.php';
        include ('./layouts/admin/main.php');
    }

?>