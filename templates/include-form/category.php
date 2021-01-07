<?php
if (isset($_POST['add_category'])) {
    $success = false;
    $error = false;
    $errno = false;
    if (trim($_POST['title_category']) !== '') {
        $title = htmlspecialchars($_POST['title_category']);
        $slug = slugify($title, array('transliterate' => true));

        if ($mysqli_stmt_obj = $mysqli->prepare("SELECT * FROM categories WHERE slug=?")) {
            if (!$mysqli_stmt_obj->bind_param('s', $slug)) {
                $errno = true; // Не удалось привязать параметры
            }

            if (!$mysqli_stmt_obj->execute()) {
                $errno = true; // Не удалось выполнить запрос.
            }
        } else {
            $errno = true; // Не удалось подготовить запрос.
        }

        if ($errno == false) {
            if ($mysqli_stmt_obj->fetch() == 0) {
                // Проверяет уникальность поля slug перед добавлением в таблицу.
                $mysqli_stmt_obj->close();
                if ($mysqli_stmt_obj = $mysqli->prepare("INSERT INTO categories(title, slug) VALUE(?, ?)")) {
                    if (!$mysqli_stmt_obj->bind_param('ss', $title, $slug)) {
                        $errno = true; // Не удалось привязать параметры
                    }
                    if (!$mysqli_stmt_obj->execute()) {
                        $errno = true; // Не удалось выполнить запрос.
                    }
                    if ($errno == false) {
                        $success = 'Категория ' . $title . ' успешно добавлена!';
                    }
                } else {
                    $errno = true; // Не удалось подготовить запрос.
                }
            } else {
                $error = 'Категория с данным названием уже существует!';
            }
        }
    }else{
        $error = 'Введите название!';
    }
}
?>
<h1>Форма добавления категории</h1>
<?php if (isset($_POST['add_category'])) { ?>
    <?php if ($error) { ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php } ?>

    <?php if ($errno) { ?>
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

<form action="" method="POST">
    <div class="mb-3">
        <label class="form-label">Название категории</label>
        <input name="title_category" type="text" class="form-control">

    </div>
    <div class="d-grid gap-2">
        <button name="add_category" type="submit" class="btn btn-dark">Добавить</button>
    </div>

</form>
