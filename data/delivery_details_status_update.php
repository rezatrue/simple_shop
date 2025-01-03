<?php
// Database connection

require 'Database.php'; 

    if(isset($_GET['status']) && isset($_GET['oid']) && isset($_GET['row'])){
        
        $status = $_GET['status'];
        $o_id = $_GET['oid'];
        $row = $_GET['row'];

        
        // we don't wnat to set un confimred after confirming any order
        // if($status == 'false'){
        //     $result = $db->confirmDelivery($o_id, 0);
        // }

        if($status == 'true'){
            // echo '<pre>';
            // print_r($row['c_items']['item']);
            // echo '</pre>';
            // exit();
            $db = new Database(); 
            // set confim order
            $result = $db->confirmDelivery($o_id, 1);

            // copy order info to closed_orders table
            $db->addToClosedOrders($row);
            // remove order from delivery_details & order_table table
            $db->removeOrder($o_id);

            $db->close();
        }
        

    }
?>