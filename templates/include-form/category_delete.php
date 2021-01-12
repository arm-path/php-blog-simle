<?php
require_once('modules/request/categories.php');
?>
<?php
$errno_db = false;
$success = false;
if (isset($_POST['delete'])) {
    if ($_POST['category_id_for_delete']) {
        $category_id = htmlspecialchars($_POST['category_id_for_delete']);
        if (filter_var($category_id, FILTER_VALIDATE_INT)) {
            if ($mysqli_stmt_obj = $mysqli->prepare("DELETE FROM categories WHERE id=?")) {
                if (!$mysqli_stmt_obj->bind_param('i', $category_id)) {
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
            $success = 'Категория успешно удалена';
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
    <label class="form-label">Выберите категорию для удаления</label>
    <select name="category_id" id="category-id" class="form-select mb-3">
        <option value="0">Статья для удаления</option>
        <?php foreach ($categories as $category) { ?>
            <option
                    value="<?= $category['id'] ?>"><?= $category['title'] ?>
            </option>
        <?php } ?>
    </select>
</div>
<div class="d-grid gap-2">
    <button name="delete_modal_category" id="delete-modal-category" type="submit" class="btn btn-dark"
            data-bs-toggle="modal" data-bs-target="#category-delete" disabled>Удалить
    </button>
</div>

<!-- Modal -->
<div class="modal fade category-delete" id=""
     tabindex="-1" aria-labelledby="category-delete-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="category-delete-label">Вы уверены?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="category_id_for_delete" id="category-id-for-delete" value="-1050"
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
    const categoryId = document.querySelector('#category-id');
    const delete_modal_category = document.querySelector('#delete-modal-category')
    const category_id_for_delete = document.querySelector('#category-id-for-delete')
    const modal = document.querySelector('.category-delete')

    categoryId.addEventListener('change', (event) => {
        if (Number(categoryId.value == 0)) {
            delete_modal_category.setAttribute('disabled', true)
            modal.id = ''
        } else if (Number(categoryId.value)) {
            delete_modal_category.removeAttribute('disabled')
            modal.id = 'category-delete';
            category_id_for_delete.value = categoryId.value
        }
    })
</script>