<?php
require_once('modules/request/categories.php');
$errno = false;
if (isset($_GET['article'])) {
    $slug = htmlspecialchars($_GET['article']);
    $query = "SELECT `id`, `title`, `category_id`, `images`, `content`, `views` FROM articles WHERE slug=?";
    if ($mysqli_stmt_obj = $mysqli->prepare($query)) {
        if (!$mysqli_stmt_obj->bind_param('s', $slug)) {
            $errno = true; // Не удалось привязать параметры

        }
        if (!$mysqli_stmt_obj->execute()) {
            $errno = true; // Не удалось выполнить запрос.
        }
        $mysqli_stmt_obj->bind_result($id, $title, $category_id, $images, $content, $views);
    } else {
        $errno = true; // Не удалось подготовить запрос.
    }

    ?>

    <?php
    if (!$errno) {
        if (!($mysqli_stmt_obj->fetch() == 0)) {
            $mysqli_stmt_obj->close();
            ?>
            <div class="card mb-3">
                <img
                        src="media/articles/<?php if ($images) echo $images; else echo 'no-image.jpg' ?>"
                        class="card-img-top img-fluid" alt="..."
                >
                <div class="card-body">
                    <h3 class="card-title"><?= $title ?></h3>
                    <h5 class="card-title">
                        <?php
                        foreach ($categories as $category) {
                            if ($category['id'] == $category_id) { ?>
                                <a href="?category=<?php echo $category['slug'] ?>"><?= $category['title']; ?> </a>

                                <?php
                            }
                        }
                        ?>

                    </h5>
                    <p class="card-text"><?= $content ?></p>
                    <p class="card-text"><small class="text-muted">Количество просмотров: <?= $views ?></small></p>
                </div>
            </div>
        <?php }
    }
} ?>

<?php
$view = $views + 1;

if ($mysqli_stmt_obj = $mysqli->prepare("UPDATE articles set views=? WHERE id=?")) {
    if (!$mysqli_stmt_obj->bind_param('ii', $view, $id)) {
        $errno = true; // Не удалось привязать параметры

    }
    if (!$mysqli_stmt_obj->execute()) {
        $errno = true; // Не удалось выполнить запрос.
    }
} else {
    $errno = true; // Не удалось подготовить запрос.
}
?>
