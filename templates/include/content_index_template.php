<?php
$query = "
          SELECT `title`, `slug`, `date_of_publication`, `images`, YEAR(date_of_publication) as year 
          FROM `articles` 
          ORDER BY `views` DESC LIMIT 3;
          ";
$fetch_result = $mysqli->query($query)
?>

<h1 style="text-align: center">Топ просматриваемых статей</h1>
<div class="container-main-top">
    <div class="row row-cols-md-4 justify-content-center">
        <?php while ($article = $fetch_result->fetch_assoc()) { ?>
            <a href="?article=<?php echo $article['slug'] ?>" class="card m-3">
                <div class="row">
                    <img
                            src="media/articles/<?php if ($article['images']) echo $article['images']; else echo 'no-image.jpg' ?>"
                            class="img-fluid p-0"
                            alt="..."
                    >
                    <div class="card-body p-2">
                        <h5 class="card-title text-center link-no-style"><?php echo $article['title'] ?></h5>
                        <p class="card-text text-center">
                            <small class="text-muted"><?php echo $article['year'] ?></small>
                        </p>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>

<?php
$query = "
          SELECT `title`, `slug`, `date_of_publication`, `images`, `content`, `date_of_publication` 
          FROM `articles` 
          ORDER BY `id` DESC LIMIT 3;
          ";
$fetch_result = $mysqli->query($query)
?>

<div class="">
    <h1 style="text-align: center">Новые статьии на сайте</h1>
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
