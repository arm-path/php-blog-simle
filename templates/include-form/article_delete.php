<?php
require_once('modules/request/articles.php');
?>
<?php
$errno_db = false;
$success = false;
if (isset($_POST['delete'])) {
    if ($_POST['article_id_for_delete']) {
        $article_id = htmlspecialchars($_POST['article_id_for_delete']);
        if (filter_var($article_id, FILTER_VALIDATE_INT)) {
            if ($mysqli_stmt_obj = $mysqli->prepare("DELETE FROM articles WHERE id=?")) {
                if (!$mysqli_stmt_obj->bind_param('i', $article_id)) {
                    $errno_db = true; // Не удалось привязать параметры
                }
                if (!$mysqli_stmt_obj->execute()) {
                    $errno_db = true; // Не удалось выполнить запрос.
                }
            } else {
                $errno_db = true; // Не удалось подготовить запрос.
            }
        }
        if ($errno_db == false) {
            $success = 'Статья успешно удалена';
        }
    }
}
?>

<h1>Форма удаления Статьии</h1>
<?php if (isset($_POST['delete'])) { ?>
    <?php if ($errno_db) { ?>
        <div class="alert alert-danger" role="alert">
            Ошибка работы с базой данных, пожалуйста обратитесь к администратору!
        </div>
    <?php } ?>

    <?php if ($success) { ?>
        <div class="alert alert-success" role="alert">
            <?= $success ?>
        </div>
    <?php } ?>
<?php } ?>
<div class="md-3">
    <label class="form-label">Выберите статью для удаления</label>
    <select name="article_id" id="article-id" class="form-select mb-3">
        <option value="0">Статья для удаления</option>
        <?php foreach ($articles as $article) { ?>
            <option value="<?= $article['id'] ?>"><?= $article['title'] ?> </option>
        <?php } ?>
    </select>
</div>
<div class="d-grid gap-2">
    <button name="delete_modal_article" id="delete-modal-article" type="submit" class="btn btn-dark"
            data-bs-toggle="modal" data-bs-target="#article-delete" disabled>Удалить
    </button>
</div>

<!-- Modal -->
<div class="modal fade article-delete" id=""
     tabindex="-1" aria-labelledby="article-delete-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="article-delete-label">Вы уверены?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="article_id_for_delete" id="article-id-for-delete" value="-1050"
                       style="display: none">
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" name="delete" class="btn btn-danger">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const articleId = document.querySelector('#article-id');
    const delete_modal_article = document.querySelector('#delete-modal-article')
    const article_id_for_delete = document.querySelector('#article-id-for-delete')
    const modal = document.querySelector('.article-delete')

    articleId.addEventListener('change', (event) => {
        if (Number(articleId.value == 0)) {
            delete_modal_article.setAttribute('disabled', true)
            modal.id = ''
        } else if (Number(articleId.value)) {
            delete_modal_article.removeAttribute('disabled')
            modal.id = 'article-delete';
            article_id_for_delete.value = articleId.value
        }
    })
</script>