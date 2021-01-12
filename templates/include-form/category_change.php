<?php
require_once('modules/request/categories.php');
?>

<h1>Форма изменения Категории</h1>

<form action="" id="get-category-for-change" method="POST">
    <div class="md-3">
        <label class="form-label">Выберите категорию для редактирования</label>
        <select name="category_id" id="category-id" class="form-select mb-3">
            <option value="0">Категория для редактирования</option>
            <?php foreach ($categories as $category) { ?>
                <option
                    value="<?= $category['id'] ?>" <?php if (isset($_POST['category_id'])) {
                    if ($_POST['category_id'] == $category['id']) { ?> selected <?php }
                } ?>><?= $category['title'] ?>
                </option>
            <?php } ?>
        </select>
    </div>
</form>
<?php

if (isset($_POST['category_id'])) {
    if ($_POST['category_id'] !== '0') {
        $category_id = htmlspecialchars($_POST['category_id']);

        $categoryValidation = true;
        if (filter_var($category_id, FILTER_VALIDATE_INT)) {
            $category_id = (integer)$category_id;
            $query = "SELECT id, title  FROM categories WHERE id=?";
            if ($mysqli_stmt_obj = $mysqli->prepare($query)) {

                if (!$mysqli_stmt_obj->bind_param('i', $category_id)) {
                    $categoryValidation = false; // Не удалось привязать параметры
                }
                if (!$mysqli_stmt_obj->execute()) {
                    $categoryValidation = false; // Не удалось выполнить запрос.
                }
                $mysqli_stmt_obj->bind_result($id_initial, $title_initial);
                // Получение результатов и определение переменных $id, $title, $slug, $category_id, $images, $content.
                if (!$mysqli_stmt_obj->fetch()) {
                    $categoryValidation = false; // Не удалось получить категорию.
                }
                $mysqli_stmt_obj->close();
            } else {
                $categoryValidation = false; // Не удалось подготовить запрос.
            }
        } else {
            $categoryValidation = false;
        } ?>

        <?php if ($categoryValidation) { ?>
            <?php require('category_change_form.php') ?>
        <?php }
    }
} ?>

<script>
    let categoryId = document.querySelector('#category-id');
    let getCategoryForChange = document.querySelector('#get-category-for-change');
    categoryId.addEventListener('change', (event) => {
        if (Number(categoryId.value == 0)) {
            getCategoryForChange.submit();
        } else if (Number(categoryId.value)) {
            getCategoryForChange.submit();
        }
    })
</script>