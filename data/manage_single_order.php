<?php
require 'Database.php'; 

class manageSingleOrder{

    function orderDetails($o_id){
        $db = new Database();
        $result = $db->orderDetails($o_id);
        $db->close();
        return $result;
    }

    function createNewOrderItem($o_id, $p_id, $o_unit, $p_size, $c_notes){
        $user_ip = $_SERVER['REMOTE_ADDR'];
        if($o_id === 0){
            $o_id =  uniqid();
        }
        $db = new Database();
        $new_o_id =  $db->addOderItem($o_id, $user_ip, $p_id, $o_unit, $p_size, $c_notes);
        $db->close();
        if($new_o_id != 0){
            header("Location: ../order_details.php?o_id=" . $new_o_id);
            exit();
        }else{
            return;
        }
        
    }



}

?>