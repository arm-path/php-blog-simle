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
                        <a href="?add=article" class="list-group-item">Добавление статьии</a>
                        <a href="?change=article" class="list-group-item">Изменение статьии</a>
                        <a href="?delete=article" class="list-group-item">Удаление статьии</a>
                        <a href="?add=category" class="list-group-item">Добавление категории</a>
                        <a href="?change=category" class="list-group-item">Изменение категории</a>
                        <a href="?delete=category" class="list-group-item">Удаление категории</a>
                    </div>
                </div>
                <div class="col-lg-9">
                    <?php if (empty($_GET)){
                        require('templates/include-form/category.php');
                    }else if(!isset($_GET['add']) && !isset($_GET['change']) && !isset($_GET['delete'])){ ;?>
                        <div class="alert alert-danger" role="alert">
                            Не существующий get запрос!
                        </div>
                    <?php exit(); } ?>
                    <?php if (isset($_GET['add'])) { ?>
                        <?php if ($_GET['add'] == 'category') { ?>
                            <?php require('templates/include-form/category.php') ?>
                        <?php } ?>
                        <?php if ($_GET['add'] == 'article') { ?>
                            <?php require('templates/include-form/article.php') ?>
                        <?php } ?>
                        <?php if ($_GET['add'] !== 'article' && $_GET['add'] !== 'category'){?>
                            <div class="alert alert-danger" role="alert">
                                Не существующий get запрос!
                            </div>
                        <?php exit();}?>
                    <?php } ?>
                    <?php if (isset($_GET['change'])) { ?>
                        <?php if ($_GET['change'] == 'article') { ?>
                            <?php require('templates/include-form/article_change.php') ?>
                        <?php } ?>
                        <?php if ($_GET['change'] == 'category') { ?>
                            <?php require('templates/include-form/category_change.php') ?>
                        <?php } ?>
                        <?php if ($_GET['change'] !== 'article' && $_GET['change'] !== 'category'){?>
                            <div class="alert alert-danger" role="alert">
                                Не существующий get запрос!
                            </div>
                            <?php exit();}?>
                    <?php } ?>
                    <?php if (isset($_GET['delete'])) { ?>
                        <?php if ($_GET['delete'] == 'article') { ?>
                            <?php require('templates/include-form/article_delete.php') ?>
                        <?php } ?>
                        <?php if ($_GET['delete'] == 'category') { ?>
                            <?php require('templates/include-form/category_delete.php') ?>
                        <?php } ?>
                        <?php if ($_GET['delete'] !== 'article' && $_GET['delete'] !== 'category'){?>
                            <div class="alert alert-danger" role="alert">
                                Не существующий get запрос!
                            </div>
                            <?php exit();}?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>

<?php require_once('templates/include/footer.php'); ?>