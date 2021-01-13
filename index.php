<?php
require_once('templates/include/head.php');
require_once('modules/request/categories.php');
?>

    <body>

    <?php require_once('templates/include/navigation_header.php') ?>

    <div class="container-page">
        <div class="container-content-page">
            <div class="row">
                <div class="col-lg-3">
                    <?php require('templates/include/navigation_category.php') ?>
                </div>
                <div class="col-lg-9">
                    <?php if (isset($_GET['category'])) {
                        $no_get = true;
                        foreach ($categories as $category) {
                            if ($_GET['category'] == $category['slug']) {
                                $category_id = $category['id'];
                                $category_title = $category['title'];
                                require('templates/include/content_category_template.php');
                                $no_get = false;
                            }
                        }
                        if ($no_get) {
                            require('templates/include/content_index_template.php');
                        }
                    } else if (isset($_GET['article'])) {
                        require('templates/include/content_get_article.php');
                    } else {
                        require('templates/include/content_index_template.php');
                    } ?>
                </div>
            </div>
        </div>
    </body>
<?php require_once('templates/include/footer.php'); ?>