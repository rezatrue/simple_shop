<?php
// Database connection

require 'Database.php'; 

$db = new Database();
$query = $_GET['query'];

$result = $db->getMainCategories($query);

$db->close();
while ($row = $result->fetch_assoc()) {
    echo '<div class="category-item">' . htmlspecialchars($row['cat_name']) . '</div>';
}
?>