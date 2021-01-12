<?php

if (isset($_POST['change_article'])) {
    $success = false;
    $error = false;
    $errno = false;
    $errno_db = false;
    $image_change = true;
// Проверяет title и создает slug.
    if (trim($_POST['title_article']) !== '') {
        $title = htmlspecialchars($_POST['title_article']);
        $slug = slugify($title, array('transliterate' => true));

    } else {
        $errno = true;
        $error = 'Введите название!';
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
            $mysqli_stmt_obj->bind_result($id_new_slug);

        } else {
            $errno_db = true; // Не удалось подготовить запрос.
        }

        if ($errno_db == false) {
            $article_id = (integer)htmlspecialchars($_POST['article_id']);
            if (($mysqli_stmt_obj->fetch() == 0) || ($article_id == $id_new_slug)) {
                // Проверяет наличие статьии с указанным slug, исключая редактируемую запись.
                $mysqli_stmt_obj->close();

                // TODO: запись NULL в БД: Конец блока кода для исправления. Укоротить код, подумать над другим решением.
                if ($images == false && $content == false) {
                    $query = "UPDATE articles SET title=?, slug=?, category_id=? WHERE id=?";
                } elseif ($images == false) {
                    $query = "UPDATE  articles SET title=?, slug=?, category_id=?, content=? WHERE id=?";
                } elseif ($content == false) {
                    $query = "UPDATE  articles SET title=?, slug=?, category_id=?, images=? WHERE id=?";
                } else {
                    $query = "UPDATE  articles SET title=?, slug=?, category_id=?, images=? content=? WHERE id=?";
                }
                if ($mysqli_stmt_obj = $mysqli->prepare($query)) {

                    if ($images == false && $content == false) {
                        if (!$mysqli_stmt_obj->bind_param('ssii', $title, $slug, $category, $article_id)) {
                            $errno_db = true; // Не удалось привязать параметры

                        }
                    } elseif ($images == false) {
                        if (!$mysqli_stmt_obj->bind_param('ssisi', $title, $slug, $category, $content, $article_id)) {
                            $errno_db = true; // Не удалось привязать параметры
                        }
                    } elseif ($content == false) {
                        if (!$mysqli_stmt_obj->bind_param('ssisi', $title, $slug, $category, $images, $article_id)) {
                            $errno_db = true; // Не удалось привязать параметры
                        }
                    } else {
                        if (!$mysqli_stmt_obj->bind_param('ssissi', $title, $slug, $category, $images, $content, $article_id)) {
                            $errno_db = true; // Не удалось привязать параметры
                        }
                    }
                    // TODO: запись NULL в БД: Конец блока кода для исправления.

                    if (!$mysqli_stmt_obj->execute()) {
                        $errno_db = true; // Не удалось выполнить запрос.
                    }
                    if ($errno_db == false) {
                        $success = 'Статья "' . $title . '" успешно изменена!';
                        if ($images !== false) {
                            move_uploaded_file($from, $to);
                        }
                    }
                } else {
                    $errno_db = true; // Не удалось подготовить запрос.
                }

            }
        } else {
            $error = 'Статья с данным названием уже существует!';
        }

    }
}
?>
<form action="" id="article-change" method="POST" enctype="multipart/form-data">
    <?php if (isset($_POST['change_article'])) { ?>
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

    <input name="article_id" id="title" type="hidden" style="display: none"
           value="<?php if (isset($_POST['article_id'])) {
               echo $_POST['article_id'];
           } else echo $id_initial ?>">
    <div class="mb-3">
        <label for="title" class="form-label">Название статьии</label>
        <input name="title_article" id="title" type="text" class="form-control"
               value="<?php if (isset($_POST['title_article'])) {
                   echo $_POST['title_article'];
               } else echo $title_initial ?>">
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
                    } else {
                        if ($category['id'] == $category_initial) {
                            echo 'selected';
                        }
                    } ?>>
                    <?php echo $category['title'] ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-3">
        Файл изображения | <?php echo $images_initial ?><br/>
        <label for="images" class="form-label">Изменить: </label>
        <input name="file" id="images" type="file" class="form-control">
        <input name="images" id="title" type="hidden" style="display: none"
               value="<?php if (isset($_POST['images'])) {
                   echo $_POST['images'];
               } else echo $images_initial ?>">
    </div>

    <div class="mb-3">
        <label for="content" class="form-label">Текст статьии</label>
        <textarea
                name="content_article" id="content" class="form-control"
                rows="3"><?php if (isset($_POST['content_article'])) {
                echo trim($_POST['content_article']);
            } else {
                echo $content_initial;
            } ?></textarea>
    </div>

    <div class="d-grid gap-2">
        <button name="change_article" type="submit" class="btn btn-dark">Сохранить</button>
    </div>
</form>
