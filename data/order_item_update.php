<?php
// Database connection

require 'Database.php'; 

    if(isset($_GET['status']) && isset($_GET['pid'])  && isset($_GET['oid'])){
        $db = new Database(); 
        $status = $_GET['status'];
        $p_id = $_GET['pid'];
        $o_id = $_GET['oid'];

        if($status){
            $result = $db->deleteOrdertItem($o_id, $p_id);
        }else{
            $u_ip = $_SERVER['REMOTE_ADDR'];
            $o_unit = $_GET['ounit'];
            $p_size = $_GET['psize'];
            $c_notes = $_GET['cnotes'];
            $result = $db->addOderItem($o_id, $u_ip, $p_id, $o_unit, $p_size, $c_notes); 
        }
        $db->close();
    }else{
        console.log('No action performed');
    }
?>