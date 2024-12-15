<?php
// Database connection
require 'Database.php'; 

$db = new Database();
$query = $_GET['query'];

$result = $db->getProductWithPartialName($query);

$db->close();
while ($row = $result->fetch_assoc()) {
    echo '<div class="product-item" data-price="' . htmlspecialchars($row['p_price']) . '" data-sizes="' . htmlspecialchars($row['p_sizes']) . '" data-pid="' . htmlspecialchars($row['p_id']) . '">' . htmlspecialchars($row['p_name']) . '</div>';
}
?>