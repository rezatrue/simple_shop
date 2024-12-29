<?php
// Database connection

require 'Database.php'; 

    if(isset($_GET['status']) && isset($_GET['oid'])){
        $db = new Database(); 
        $status = $_GET['status'];
        $o_id = $_GET['oid'];
        
        if($status == 'false'){
            $result = $db->confirmDelivery($o_id, 0);
        }

        if($status == 'true'){

            $result = $db->confirmDelivery($o_id, 1);
        }
        $db->close();

    }
?>