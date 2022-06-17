<?php
$blog = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/translate.php");

header('X-XSS-Protection "1; mode=block"');

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
else
    $url = "http://";

$url .= $_SERVER['HTTP_HOST'];
$url .= $_SERVER['REQUEST_URI'];

session_start();

$mysqli = new mysqli($rpxycfg['server'], $rpxycfg['user'], $rpxycfg['psw'], $rpxycfg['dbname']);

if (!$mysqli) {
    die("Could not connect to DB: " . $mysqli->connect_error);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?= $blog['name'] ?>
    </title>
    <link rel="stylesheet" href="/repoxy/layouts/default/css/base.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/home.css">

    <!-- Buttons -->
    <link rel="stylesheet" href="/repoxy/layouts/default/css/buttons.css">
    <link rel="icon" href="/repoxy/layouts/default/assets/logo.png" type="image/png">

    <meta name="title" content="<?= $blog['name'] ?>">
    <meta name="description" content="<?= $blog['desc'] ?>">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $url ?>">
    <meta property="og:title" content="<?= $blog['name'] ?>">
    <meta property="og:description" content="<?= $blog['desc'] ?>">
    <meta property="og:image" content="<?= $url ?>repoxy/layouts/default/assets/logo.png">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $url ?>">
    <meta property="twitter:title" content="<?= $blog['name'] ?>">
    <meta property="twitter:description" content="<?= $blog['desc'] ?>">
    <meta property="twitter:image" content="<?= $url ?>repoxy/layouts/default/assets/logo.png">
    <meta name="generator" content="Repoxy <?= $rpxycfg['version'] ?>" />

</head>

<body>
    <main>
        <header>
            <h1 class="blogname"><?= $blog['name'] ?></h1>
            <div class="header_links">
                <ul>
                    <a href="/">
                        <li class="header_link link_active"><?= i18('homepage', 'sess') ?></li>
                    </a>
                    <a href="/about">
                        <li class="header_link"><?= i18('about', 'sess') ?></li>
                    </a>
                    <a href="/contacts">
                        <li class="header_link"><?= i18('contacts', 'sess') ?></li>
                    </a>
                    <?php if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) : ?>
                        <a href="/admpanel">
                            <li class="header_link"><?= i18('adminpanel', 'sess') ?></li>
                        </a>
                    <?php endif ?>
                </ul>
            </div>
        </header>
        <div id="posts">
            <?php
            $results_per_page = 10;
            $query = "SELECT * FROM posts";
            $result = $mysqli->query($query);
            $number_of_result = $result->num_rows;
            $number_of_page = ceil($number_of_result / $results_per_page);

            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = $_GET['page'];
            }

            $page_first_result = ($page - 1) * $results_per_page;

            $query = "SELECT * FROM posts ORDER BY postcreatedAt DESC LIMIT " . $page_first_result . ',' . $results_per_page;
            $result = $mysqli->query($query);

            while ($row = $result->fetch_assoc()) {
                if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) { // if admin, the "Manage post" button appears
                    echo "<div class=\"post\"><div class=\"post_head\"><h1 class=\"post_heading\">{$row["postname"]}</h1><p class=\"post_subheading\"> " . i18('by', 'sess') . " <b>{$blog['author']}</b> " . i18('at', 'sess') . " {$row["postcreatedAt"]}</p></div><div class=\"post_text\">{$row["postcontent"]}</div><div class=\"btns\"><a href=\"/view/?post={$row['postid']}\"><button class=\"btn-blue\" style=\"margin-bottom: 5px; margin-right: 1em;\">" . i18('contreading', 'sess') . "</button></a><a href=\"/repoxy/modules/deletePost.php?postid={$row['postid']}\"><button class=\"btn-red\">" . i18('delete', 'sess') . "</button></a></div></div>";
                } else {
                    echo "<div class=\"post\"><div class=\"post_head\"><h1 class=\"post_heading\">{$row["postname"]}</h1><p class=\"post_subheading\"> " . i18('by', 'auto') . " <b>{$blog['author']}</b> " . i18('at', 'auto') . " {$row["postcreatedAt"]}</p></div><div class=\"post_text\">{$row["postcontent"]}</div><div class=\"btns\"><a href=\"/view/?post={$row['postid']}\"><button class=\"btn-blue\">" . i18('contreading', 'auto') . "</button></a></div></div>";
                }
            }

            $mysqli->close();

            echo '<div class="paglinks">';
            for ($page = 1; $page <= $number_of_page; $page++) {
                echo '<a href="?page=' . $page . '">' . $page . ' </a>';
            }
            echo '</div>';
            ?>
        </div>
    </main>
    <script src="/repoxy/layouts/default/js/descCut.js"></script>
</body>

</html>