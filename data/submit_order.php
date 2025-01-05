<?php
session_start();
require 'Database.php'; 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $generatedUniqueId = null;
    // Get the user's IP address
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $message = null; // if not null we will send it via session

    if (isset($_POST['submit'])) {
        $action = $_POST['submit'];
        
        if ($action === 'order') {
            echo "order submitted";

            if (isset($_SESSION['cart'])) {
                $db = new Database();
                foreach ($_SESSION['cart'] as $id => $item) {
                    if ($generatedUniqueId == null){
                        $generatedUniqueId =  uniqid();
                    }

                    $db->addOderItem($generatedUniqueId, '', $user_ip, $id, (int)$item['quantity'], $item['size'], $item['notes']); // 
                    // Remove products from session
                    if (isset($_SESSION['cart'][$id])) {
                        unset($_SESSION['cart'][$id]);
                    }
                }
                $db->deliveryDetails($generatedUniqueId, '', '', '', '');
                $db->close();
            }else {
                echo "0 results";
            }
        }
    }    
 
    header("Location: ../thank_you.php?order_id=" . $generatedUniqueId);
    exit();
}

?>