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

$mysqli = new mysqli($rpxycfg['server'], $rpxycfg['user'], $rpxycfg['psw'], $rpxycfg['dbname']);

if (!$mysqli) {
    die("Could not connect to DB: " . $mysqli->connect_error);
}

if (isset($_GET['post'])) {
    try {
        $result = $mysqli->query('SELECT postname from posts WHERE postid = ' . htmlspecialchars($_GET['post'], ENT_QUOTES, 'UTF-8'));
        $row = $result->fetch_assoc();
        if ($result->num_rows > 0) {
            $postname = $row['postname'];
        } else {
            $postname = '404';
            return header('Location: /404.html');
        }
    } catch (\Throwable $th) {
        $postname = '404';
        return die('404');
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?= $blog['name'] ?> - <?= $postname ? $postname : '404' ?>
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
            $query = "SELECT postid, postname, postcontent, postcreatedAt FROM posts WHERE postid = " . $_GET['post'];
            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();
            if ($result->num_rows > 0) {
                // output data of each post
                if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) { // if admin, the "Manage post" button appears
                    echo "<div class=\"post\"><div class=\"post_head\"><h1 class=\"post_heading\">{$row["postname"]}</h1><p class=\"post_subheading\"> " . i18('by', 'sess') . " <b>{$blog['author']}</b> " . i18('at', 'sess') . " {$row["postcreatedAt"]}</p></div><div class=\"post_text\">{$row["postcontent"]}</div><div class=\"btns\"><a href=\"/repoxy/modules/deletePost.php?postid={$row['postid']}\"><button class=\"btn-red\">" . i18('delete', 'sess') . "</button></a></div></div>";
                } else {
                    echo "<div class=\"post\"><div class=\"post_head\"><h1 class=\"post_heading\">{$row["postname"]}</h1><p class=\"post_subheading\"> " . i18('by', 'sess') . " <b>{$blog['author']}</b> " . i18('at', 'sess') . " {$row["postcreatedAt"]}</p></div><div class=\"post_text\">{$row["postcontent"]}</div></div>";
                }
            } else {
                echo "<p style=\"text-align: center;\">404</p>";
            }

            $mysqli->close();
            ?>
        </div>
    </main>
</body>

</html>