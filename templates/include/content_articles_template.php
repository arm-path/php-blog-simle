<?php
$page_count = 1; // Количество статей на странице.
$query = "SELECT COUNT(*) as count FROM `articles`";
$fetch_result_page = $mysqli->query($query);
$count_article = $fetch_result_page->fetch_assoc();
$count_article = $count_article['count']; // Количество статей.
$count_pages = intval(($count_article - 1) / $page_count) + 1; // Количество страниц.

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $page = intval($page);
    if (empty($page) or $page < 0) $page = 1;
    if ($page > $count_pages) $page = $count_pages;
    $start = $page * $page_count - $page_count;
} else {
    $start = 0;
}

$query = "
          SELECT `title`, `slug`, `date_of_publication`, `images`, `content`, `date_of_publication` 
          FROM `articles` 
          ORDER BY `id` LIMIT $start, $page_count;
          ";
$fetch_result = $mysqli->query($query)
?>

<div class="mb-5">
    <h1 style="text-align: center">Список статей</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4 mt-3">
        <?php while ($article = $fetch_result->fetch_assoc()) { ?>
            <div class="col">
                <div class="card">
                    <img
                            src="media/articles/<?php if ($article['images']) echo $article['images']; else echo 'no-image.jpg' ?>"
                            class="card-img-top img-fluid"
                            alt="..."
                    >
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="?article=<?php echo $article['slug'] ?>"
                               class="text-decoration-none link-article">
                                <?php echo $article['title'] ?>
                            </a>
                        </h5>
                        <p class="card-text"><?php echo substr($article['content'], 0, 125) ?></p>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Дата публикации: <?php echo $article['date_of_publication'] ?></small>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<hr/>

<?php if ($count_pages > 1) {
    if (!isset($_GET['page'])) {
        $_GET['page'] = 1;
    } ?>
    <nav aria-label="Page navigation example mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if (($_GET['page'] - 1) <= 0) { ?> disabled <?php } ?>">
                <a class="page-link" href="?page=<?= ($_GET['page'] - 1) ?>">Назад</a>
            </li>
            <?php for ($num_page = 1; $num_page <= $count_pages; $num_page++) { ?>
                <li class="page-item <?php if ($_GET['page'] == $num_page) { ?> active <?php } ?>">
                    <a class="page-link" href="?page=<?= $num_page ?>"><?= $num_page ?></a>
                </li>
            <?php } ?>
            <li class="page-item <?php if (($_GET['page'] + 1) > $count_pages) { ?> disabled <?php } ?>">
                <a class="page-link" href="?page=<?= ($_GET['page'] + 1) ?>">Вперед</a>
            </li>
        </ul>
    </nav>
<?php } ?>

