<?php
$fetch_result = $mysqli->query("SELECT * FROM `categories`;");
$fetch_result->data_seek(0);
$category_show = $fetch_result->num_rows;

?>

<h1 class="my-4">Категории</h1>
<?php if ($category_show) { ?>
<div class="list-group list-category">
    <?php while ($category = $fetch_result->fetch_assoc()) { ?>
        <a href="?category=<?php echo $category['slug'] ?>"
           class="list-group-item"><?php echo $category['title'] ?></a>
    <?php } ?>
</div>
<?php } else { ?>
    <div class="alert alert-warning" role="alert">
        Добавить <a href="/blog-php/configuration.php?add=category">категории...</a>
    </div>
<?php } ?>