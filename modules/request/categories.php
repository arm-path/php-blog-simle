<?php
$fetch_result = $mysqli->query('SELECT * FROM `categories`');
$categories = [];
while ($category = $fetch_result->fetch_assoc()) {
    $categories[] = $category;
}
?>