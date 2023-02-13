<?php
$blog    = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/translate.php");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sanitize.php");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/httptohttps.php");

header('X-XSS-Protection "1; mode=block"');

httptohttps();

$mysqli = new mysqli(
    base64_decode($rpxycfg['server']),
    base64_decode($rpxycfg['user']),
    base64_decode($rpxycfg['psw']),
    base64_decode($rpxycfg['dbname'])
);

if (!$mysqli)
    die("Could not connect to DB: {$mysqli->connect_error}");

if (isset($_GET['id'])) {
    try {
        $result = $mysqli->query("SELECT * from `posts` WHERE `postid` = " . sanitize($_GET['id']));
        $row    = $result->fetch_assoc();

        if ($result->num_rows > 0) {
            $postname    = $row['postname'];
            $postcontent = [
                'full' => $row['postcontent'],
                'shortnohtml' => substr(strip_tags($row['postcontent']), 0, 400)
            ];
        } else {
            $postname = '404';
            return header('Location: /404.html');
        }
    } catch (Throwable) {
        $postname = 'Internal Error';
        return header('Location: /404.html');
    }
} else header('Location: /404.html');
?>

<!DOCTYPE html>
<html lang="<?= base64_decode($blog['lang']) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
    <?= $postname ?> - <?= base64_decode($blog['name']) ?>
    </title>
    <link rel="stylesheet" href="/repoxy/layouts/default/css/base.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/home.css">

    <link rel="stylesheet" href="/repoxy/layouts/default/css/textStyles.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/buttons.css">
    <link rel="icon" href="/repoxy/layouts/default/assets/logo.png" type="image/png">

    <meta name="title" content="<?= $postname ?> - <?= base64_decode($blog['name']) ?>">
    <meta name="description" content="<?= $postcontent['shortnohtml'] ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $postname ?> - <?= base64_decode($blog['name']) ?>">
    <meta property="og:description" content="<?= $postcontent['shortnohtml'] ?>">
    <meta property="og:image" content="/repoxy/layouts/default/assets/logo.png">
    <meta name="generator" content="Repoxy <?= $rpxycfg['version'] ?>" />

</head>

<body>
    <main>
        <header>
            <h1 class="blogname"><?= base64_decode($blog['name']) ?></h1>
            <div class="header_links">
                <ul>
                    <a href="/">
                        <li class="header_link link_active"><?= i18n('homepage') ?></li>
                    </a>
                    <a href="/about">
                        <li class="header_link"><?= i18n('about') ?></li>
                    </a>
                    <a href="/contacts">
                        <li class="header_link"><?= i18n('contacts') ?></li>
                    </a>
                    <?php
                    if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])): ?>
                                                    <a href="/admpanel">
                                                        <li class="header_link"><?= i18n('adminpanel') ?></li>
                                                    </a>
                    <?php endif ?>
                </ul>
            </div>
        </header>
        <section name="post">
            <?php
            // @TODO: try to $mysqli->bind_param() here
            $result     = $mysqli->query("SELECT * from `posts` WHERE `postid` = " . sanitize($_GET['id']));
            $row        = $result->fetch_assoc();
            $blogAuthor = base64_decode($blog['author']);

            // post
            if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) { // if admin, the "Manage post" button appears
                echo "<div class=\"post\">
                    <div class=\"post_head\">
                    <h1 class=\"post_heading\">{$postname}</h1>
                    <p class=\"post_subheading\">
                        " . i18n('by') . " <b>{$blogAuthor}</b>
                       " . i18n('at') . " {$row["postcreated"]}</p>
                    </div>
                    <div class=\"post_text\">{$postcontent['full']}</div>
                    <div class=\"btns\">
                    <a href=\"/repoxy/modules/deletePost.php?postid={$row['postid']}&cleanOutput=true\">
                    <button class=\"btn-red\">" . i18n('delete') . "</button></a>
                    </div>
                    </div>";
            } else {
                echo "<div class=\"post\">
                    <div class=\"post_head\">
                    <h1 class=\"post_heading\">{$postname}</h1>
                    <p class=\"post_subheading\"> 
                    " . i18n('by') . " <b>{$blogAuthor}</b> "
                    . i18n('at') . " {$row["postcreated"]}</p>
                    </div>
                    <div class=\"post_text\">{$postcontent['full']}</div>
                    </div>";
            }

            $mysqli->close();
            ?>
        </section>
    </main>
</body>

</html>