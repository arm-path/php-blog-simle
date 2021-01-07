<?php
require_once('modules/request/categories.php')
?>
<?php
if (isset($_POST['add_article'])) {
    $success = false;
    $error = false;
    $errno = false;
    $errno_db = false;
    // Проверяет title и создает slug.
    if (trim($_POST['title_article']) !== '') {
        $title = htmlspecialchars($_POST['title_article']);
        $slug = slugify($title, array('transliterate' => true));
    } else {
        $errno = true;
        $error = 'Введите название!';
    }
    // Проверяет сategory и преобразует в целочисленный тип данных.
    $category = htmlspecialchars($_POST['category_article']);
    if ($errno == false) {
        foreach ($categories as $category_obj) {
            $errno = true;
            if ($category_obj['id'] == $category) {
                $errno = false;
                $category = (integer)$category;
                break;
            }
        }
    }

    // Проверяет images
    if (!empty($_FILES['file']['size'])) {
        if ($_FILES['file']['size'] > (5 * 1024 * 1024)) {
            $errno = true;
            $error = 'Размер файла не должен превышать 5Мб';
        }
        $image_info = getimagesize($_FILES['file']['tmp_name']);
        $arr = ['image/jpeg', 'image/gif', 'image/png'];
        if (!in_array($image_info['mime'], $arr)) {
            $errno = true;
            $error = 'Изображение должна быть формата JPG, GIF или PNG';
        } else {
            $upload_dir = 'media/articles/'; // Имя дирректории с изображениями.
            $name_unique = uniqid() . '&';
            $images = htmlspecialchars(date('Y-m-d&') . $name_unique . basename($_FILES['file']['name']));
            $to = $upload_dir . $images;
            $from = $_FILES['file']['tmp_name'];
            // move_uploaded_file ( string $from , string $to ) : bool
            unset($_FILES['file']);
        }
    } else {
        $images = false;
    }

    // Проверяет content
    if (trim($_POST['content_article']) !== '') {
        $content = htmlspecialchars($_POST['content_article']);
    } else {
        $content = false;
    }

    if ($errno == false) {
        // Проверяет уникальность названия, проверяя slug.
        if ($mysqli_stmt_obj = $mysqli->prepare("SELECT id FROM articles WHERE slug=?")) {
            if (!$mysqli_stmt_obj->bind_param('s', $slug)) {
                $errno_db = true; // Не удалось привязать параметры
            }

            if (!$mysqli_stmt_obj->execute()) {
                $errno_db = true; // Не удалось выполнить запрос.
            }
        } else {
            $errno_db = true; // Не удалось подготовить запрос.
        }

        if ($errno_db == false) {

            if ($mysqli_stmt_obj->fetch() == 0) {
                // Проверяет уникальность поля slug перед добавлением в таблицу.
                $mysqli_stmt_obj->close();

                // TODO: запись NULL в БД: Конец блока кода для исправления. Укоротить код, подумать над другим решением.
                if ($images == false && $content == false) {
                    $query = "INSERT INTO articles(title, slug, category_id) VALUE(?, ?, ?)";
                } elseif ($images == false) {
                    $query = "INSERT INTO articles(title, slug, category_id, content) VALUE(?, ?, ?, ?)";
                } elseif ($content == false) {
                    $query = "INSERT INTO articles(title, slug, category_id, images) VALUE(?, ?, ?, ?)";
                } else {
                    $query = "INSERT INTO articles(title, slug, category_id, images, content) VALUE(?, ?, ?, ?,?)";
                }
                if ($mysqli_stmt_obj = $mysqli->prepare($query)) {

                    if ($images == false && $content == false) {
                        if (!$mysqli_stmt_obj->bind_param('ssi', $title, $slug, $category)) {
                            echo '111';
                            $errno_db = true; // Не удалось привязать параметры

                        }
                    } elseif ($images == false) {
                        if (!$mysqli_stmt_obj->bind_param('ssis', $title, $slug, $category, $content)) {
                            $errno_db = true; // Не удалось привязать параметры
                        }
                    } elseif ($content == false) {
                        if (!$mysqli_stmt_obj->bind_param('ssis', $title, $slug, $category, $images)) {
                            $errno_db = true; // Не удалось привязать параметры
                        }
                    } else {
                        if (!$mysqli_stmt_obj->bind_param('ssiss', $title, $slug, $category, $images, $content)) {
                            $errno_db = true; // Не удалось привязать параметры
                        }
                    }
                    // TODO: запись NULL в БД: Конец блока кода для исправления.

                    if (!$mysqli_stmt_obj->execute()) {
                        $errno_db = true; // Не удалось выполнить запрос.
                    }
                    if ($errno_db == false) {
                        $success = 'Статья "' . $title . '" успешно добавлена!';
                        if ($images !== false) {
                            move_uploaded_file($from, $to);
                        }
                    }
                } else {

                    $errno_db = true; // Не удалось подготовить запрос.
                }
            } else {
                $error = 'Статья с данным названием уже существует!';
            }
        }
    }
}
?>
<h1>Форма добавления Статьии</h1>
<?php if (isset($_POST['add_article'])) { ?>
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
<form action="" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title" class="form-label">Название статьии</label>
        <input name="title_article" id="title" type="text" class="form-control"
               value="<?php if (isset($_POST['title_article'])) {
                   echo $_POST['title_article'];
               } ?>">
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Категория статьии</label>
        <select name="category_article" id="category" class="form-select" aria-label="Default select example">
            <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['id'] ?>"
                    <?php if (isset($_POST['category_article'])) {
                        if ($category['id'] == $_POST['category_article']) {
                            echo 'selected';
                        }
                    } ?>>
                    <?php echo $category['title'] ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="images" class="form-label">Файл изображения</label>
        <input name="file" id="images" type="file" class="form-control">
    </div>
    <div class="mb-3">
        <label for="content" class="form-label">Текст статьии</label>
        <textarea
                name="content_article" id="content" class="form-control"
                rows="3"><?php if (isset($_POST['content_article'])) {
                echo trim($_POST['content_article']);
            } ?></textarea>
    </div>

    <div class="d-grid gap-2">
        <button name="add_article" type="submit" class="btn btn-dark">Добавить</button>
    </div>

</form>
