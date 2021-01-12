<?php
    $fetch_result = $mysqli->query('SELECT id, title, slug FROM articles ORDER BY title');
    $articles = [];
    while ($article = $fetch_result->fetch_assoc()){
        $articles[] = $article;
    }
?>
