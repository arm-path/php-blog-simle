<?php
require_once('templates/include/head.php');
require_once('modules/library/slugify.php');

?>

<body>

<?php require_once('templates/include/navigation_header.php') ?>

<div class="container-page">
    <div class="container-content-page">
        <div class="row">
            <div class="col-lg-3">
                <h1 class="my-4">Разделы</h1>
                <div class="list-group list-category">
                    <a href="" class="list-group-item">Добавление статьии</a>
                    <a href="" class="list-group-item">Изменение статьии</a>
                    <a href="" class="list-group-item">Удаление статьии</a>
                    <a href="?add=category" class="list-group-item">Добавление категории</a>
                    <a href="?add=category" class="list-group-item">Изменение категории</a>
                    <a href="?add=category" class="list-group-item">Удаление категории</a>
                    <a href="" class="list-group-item">Конфигурирование сайта</a>
                </div>
            </div>
            <div class="col-lg-9">
                <?php require('templates/include-form/article.php') ?>
                <?php if (isset($_GET['add'])) { ?>
                    <?php if ($_GET['add'] == 'category') { ?>
                        <?php require('templates/include-form/category.php') ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
