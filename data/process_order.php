<?php
require 'Database.php'; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    
        $o_id = isset($_POST['o_id']) ? trim($_POST['o_id']) : '0';
        
        if($o_id == 0){
            header("Location: ../order_list.php");
            exit();
        }
        else{
            
                if (isset($_POST['submit_confirm'])) {
                    //echo $_POST['submit_confirm'];
                    if(!empty($o_id)){
                        $db = new Database();
                        $db->confirmOrders($o_id);
                        $db->close();
                        header("Location: ../confirm_orders.php");
                        exit();
                    }

                }

                elseif (isset($_POST['submit_cancel'])) {
                    //echo $_POST['submit_cancel'];
                    $notes = isset($_POST['reason']) ? trim($_POST['reason']) : '';

                    if(!empty($notes) && !empty($o_id)){
                        $db = new Database();
                        $db->cancelOrder($o_id, $notes);
                        $db->close();
                        header("Location: ../order_list.php");
                        exit();
                    }else{
                        header("Location: ../order_details.php?o_id=" . $o_id);
                        exit(); 
                    }
                    
                }

                else {
                    header("Location: ../order_list.php?o_id=" . $o_id);
                    exit();
                }     
                    
            }    

        }

?>