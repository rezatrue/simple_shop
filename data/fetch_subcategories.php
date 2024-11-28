<?php
// Database connection
include 'Database.php';

$db = new Database();
$category = $_GET['category'];
$result = $db->getSubCategories($category);

$db->close();

while ($row = $result->fetch_assoc()) {
    echo '<div class="subcategory-item" value="' . htmlspecialchars($row['cat_id']) . '">' . htmlspecialchars($row['cat_name']) . '</div>';
}
?>