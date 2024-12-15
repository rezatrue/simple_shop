<?php
// Database connection

require 'Database.php'; 


if(isset($_GET['units']) && isset($_GET['pid'])  && isset($_GET['oid'])){
    $db = new Database(); 
    $units = $_GET['units'];
    $p_id = $_GET['pid'];
    $o_id = $_GET['oid'];

    $result = $db->updateOrdertUnit($o_id, $p_id, $units);

    $db->close();

    if($units > 0){
        echo '<div class="badge bg-success text-wrap" style="width: 6rem;">Unites updated</div>';
    }else{
        echo '<div class="badge bg-danger text-wrap" style="width: 6rem;">Item will be removed if set Zero or less</div>';
    }
} 
else{
    echo '<div class="badge bg-danger text-wrap" style="width: 6rem;">Unit not Changed</div>';
}

?>