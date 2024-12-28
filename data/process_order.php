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
                    $action = $_POST['submit_confirm'];
                    echo $action;
                }

                elseif (isset($_POST['submit_cancel'])) {
                    //echo $_POST['submit_cancel'];
                    $notes = isset($_POST['reason']) ? trim($_POST['reason']) : '';

                    if($notes && $o_id){
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

        $search_pid = isset($_POST['search-pid']) ? intval($_POST['search-pid']) : 0;
        $search_name = isset($_POST['search-name']) ? trim($_POST['search-name']) : '';
        $search_unit = isset($_POST['search-unit']) ? floatval($_POST['search-unit']) : 0;
        $search_size = isset($_POST['search-size']) ? trim($_POST['search-size']) : '';
        $search_comment = isset($_POST['search-comment']) ? trim($_POST['search-comment']) : '';
    
        // Validate input fields
        if (!empty($search_name) && !empty($search_unit) ) {
            $db = new Database();
            $new_o_id =  $db->addOderItem($o_id, $user_ip, $search_pid, $search_unit, $search_size, $search_comment);
            $db->close();
        }

            // echo '<pre>';
            // print_r($o_id);
            // echo '</pre>';

?>