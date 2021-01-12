<?php require_once('templates/include/head.php'); ?>

<body>

<?php require_once('templates/include/navigation_header.php') ?>

<div class="container-page">
    <div class="container-content-page">
        <div class="row">
            <div class="col-lg-3">
                <?php require('templates/include/navigation_category.php') ?>
            </div>
            <div class="col-lg-9">
                <?php require('templates/include/content_index_template.php') ?>
            </div>
        </div>
    </div>
</body>
<?php require_once('templates/include/footer.php'); ?>