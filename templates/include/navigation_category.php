<?php
    $fetch_result = $mysqli->query("SELECT * FROM `categories`;");
    $fetch_result->data_seek(0);

?>

<h1 class="my-4">Категории</h1>
<div class="list-group list-category">
    <?php while ($category = $fetch_result->fetch_assoc()) { ?>
        <a href="?category=<?php echo $category['slug'] ?>"
           class="list-group-item"><?php echo $category['title'] ?></a>
    <?php } ?>
</div>