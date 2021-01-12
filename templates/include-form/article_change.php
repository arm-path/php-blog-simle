<?php
require_once('modules/request/categories.php');
require_once('modules/request/articles.php');

?>

<h1>Форма редактирования Статьии</h1>

<form action="" id="get-article-for-change" method="POST">
    <div class="md-3">
        <label class="form-label">Выберите статью для редактирования</label>
        <select name="article_id" id="article-id" class="form-select mb-3">
            <option value="0">Статья для редактирования</option>
            <?php foreach ($articles as $article) { ?>
                <option
                        value="<?= $article['id'] ?>" <?php if (isset($_POST['article_id'])) {
                    if ($_POST['article_id'] == $article['id']) { ?> selected <?php }
                } ?>><?= $article['title'] ?>
                </option>
            <?php } ?>
        </select>
    </div>
</form>
<?php

if (isset($_POST['article_id'])) {
    if ($_POST['article_id'] !== '0') {
        $article_id = htmlspecialchars($_POST['article_id']);

        $articleIdValidation = true;
        if (filter_var($article_id, FILTER_VALIDATE_INT)) {
            $article_id = (integer)$article_id;
            $query = "SELECT id, title, slug, category_id, 	images, content  FROM articles WHERE id=?";
            if ($mysqli_stmt_obj = $mysqli->prepare($query)) {

                if (!$mysqli_stmt_obj->bind_param('i', $article_id)) {
                    $articleIdValidation = false; // Не удалось привязать параметры
                }
                if (!$mysqli_stmt_obj->execute()) {
                    $articleIdValidation = false; // Не удалось выполнить запрос.
                }
                $mysqli_stmt_obj->bind_result($id_initial, $title_initial, $slug_initial, $category_initial, $images_initial, $content_initial);
                // Получение результатов и определение переменных $id, $title, $slug, $category_id, $images, $content.
                if (!$mysqli_stmt_obj->fetch()) {
                    $articleIdValidation = false; // Не удалось получить статью.
                }
                $mysqli_stmt_obj->close();
            } else {
                $articleIdValidation = false; // Не удалось подготовить запрос.
            }
        } else {
            $articleIdValidation = false;
        } ?>

        <?php if ($articleIdValidation) { ?>
            <?php require('article_change_form.php') ?>
        <?php }
    }
} ?>

<script>
    let articleId = document.querySelector('#article-id');
    let getArticleForChange = document.querySelector('#get-article-for-change');
    articleId.addEventListener('change', (event) => {
        if (Number(articleId.value == 0)) {
            getArticleForChange.submit();
        } else if (Number(articleId.value)) {
            getArticleForChange.submit();
        }
    })
</script>


