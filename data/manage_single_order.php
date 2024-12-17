<?php
require 'Database.php'; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    
        $o_id = isset($_POST['o_id']) ? trim($_POST['o_id']) : 0;
        $search_pid = isset($_POST['search-pid']) ? intval($_POST['search-pid']) : 0;
        $search_name = isset($_POST['search-name']) ? trim($_POST['search-name']) : '';
        $search_unit = isset($_POST['search-unit']) ? floatval($_POST['search-unit']) : 0;
        $search_size = isset($_POST['search-size']) ? trim($_POST['search-size']) : '';
        $search_comment = isset($_POST['search-comment']) ? trim($_POST['search-comment']) : '';
    
        // Validate input fields
        if (!empty($search_name) && !empty($search_unit) ) {

            $user_ip = $_SERVER['REMOTE_ADDR'];
            if($o_id == 0){
                $o_id =  uniqid();
            }

            $db = new Database();
            $new_o_id =  $db->addOderItem($o_id, $user_ip, $search_pid, $search_unit, $search_size, $search_comment);
            $db->close();

        }
        if($new_o_id)
            header("Location: ../order_details.php?o_id=" . $new_o_id);
        if($o_id)
            header("Location: ../order_details.php?o_id=" . $o_id);
        else
            header("Location: ../order_details.php");
        exit();
            // echo '<pre>';
            // print_r($o_id);
            // echo '</pre>';
            // exit();

    } 

?>