<?php

if (isset($_POST['change_category'])) {
    $success = false;
    $error = false;
    $errno = false;
    $errno_db = false;

// Проверяет title и создает slug.
    if (trim($_POST['title_category']) !== '') {
        $title = htmlspecialchars($_POST['title_category']);
        $slug = slugify($title, array('transliterate' => true));
    } else {
        $errno = true;
        $error = 'Введите название!';
    }

    if ($errno == false) {
        // Проверяет уникальность названия, проверяя slug.
        if ($mysqli_stmt_obj = $mysqli->prepare("SELECT id FROM categories WHERE slug=?")) {
            if (!$mysqli_stmt_obj->bind_param('s', $slug)) {
                $errno_db = true; // Не удалось привязать параметры
            }

            if (!$mysqli_stmt_obj->execute()) {
                $errno_db = true; // Не удалось выполнить запрос.
            }
            $mysqli_stmt_obj->bind_result($id_new_slug);

        } else {
            $errno_db = true; // Не удалось подготовить запрос.
        }

        if ($errno_db == false) {
            $category_id = (integer)htmlspecialchars($_POST['category_id']);
            if (($mysqli_stmt_obj->fetch() == 0) || ($category_id == $id_new_slug)) {
                $mysqli_stmt_obj->close();
                $query = "UPDATE  categories SET title=?, slug=? WHERE id=?";
                if ($mysqli_stmt_obj = $mysqli->prepare($query)) {
                    if (!$mysqli_stmt_obj->bind_param('ssi', $title, $slug, $category_id)) {
                        $errno_db = true; // Не удалось привязать параметры
                    }
                    if (!$mysqli_stmt_obj->execute()) {
                        $errno_db = true; // Не удалось выполнить запрос.
                    }
                    if ($errno_db == false) {
                        $success = 'Категория "' . $title . '" успешно изменена!';
                    }
                } else {
                    $errno_db = true; // Не удалось подготовить запрос.
                }

            } else {
                $error = 'Категория с данным названием уже существует!';
            }
        }
    }
}
?>
<form action="" id="category-change" method="POST">
    <?php if (isset($_POST['change_category'])) { ?>
        <?php if ($error) { ?>
            <div class="alert alert-danger" role="alert">
                <?= $error ?>
            </div>
        <?php } ?>

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
    <input name="category_id" id="title" type="hidden" style="display: none"
           value="<?php if (isset($_POST['category_id'])) {
               echo $_POST['category_id'];
           } else echo $id_initial ?>">
    <div class="mb-3">
        <label for="title" class="form-label">Название</label>
        <input name="title_category" id="title" type="text" class="form-control"
               value="<?php if (isset($_POST['title_category'])) {
                   echo $_POST['title_category'];
               } else echo $title_initial ?>">
    </div>

    <div class="d-grid gap-2">
        <button name="change_category" type="submit" class="btn btn-dark">Сохранить</button>
    </div>
</form>

