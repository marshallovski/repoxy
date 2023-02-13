<?php
$blog    = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/translate.php");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/httptohttps.php");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sanitize.php");

header('X-XSS-Protection "1; mode=block"');

httptohttps();

session_start();

$mysqli = new mysqli(base64_decode($rpxycfg['server']), base64_decode($rpxycfg['user']), base64_decode($rpxycfg['psw']), base64_decode($rpxycfg['dbname']));

if (!$mysqli)
    die("Could not connect to DB: {$mysqli->connect_error}");
?>

<!DOCTYPE html>
<html lang="<?= base64_decode($blog['lang']) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?= base64_decode($blog['name']) ?>
    </title>
    <link rel="stylesheet" href="/repoxy/layouts/default/css/base.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/home.css">

    <link rel="stylesheet" href="/repoxy/layouts/default/css/buttons.css">
    <link rel="icon" href="/repoxy/layouts/default/assets/logo.png" type="image/png">

    <meta name="title" content="<?= base64_decode($blog['name']) ?>">
    <meta name="description" content="<?= base64_decode($blog['desc']) ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= base64_decode($blog['name']) ?>">
    <meta property="og:description" content="<?= base64_decode($blog['desc']) ?>">
    <meta property="og:image" content="/repoxy/layouts/default/assets/logo.png">
    <meta name="generator" content="Repoxy <?= $rpxycfg['version'] ?>" />
</head>

<body>
    <main>
        <header>
            <h1 class="blogname">
                <?= base64_decode($blog['name']) ?>
            </h1>
            <div class="header_links">
                <ul>
                    <a href="/">
                        <li class="header_link link_active">
                            <?= i18n('homepage') ?>
                        </li>
                    </a>
                    <a href="/about">
                        <li class="header_link"><?= i18n('about') ?></li>
                    </a>
                    <a href="/contacts">
                        <li class="header_link">
                            <?= i18n('contacts') ?>
                        </li>
                    </a>
                    <?php if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])): ?>
                                         <a href="/admpanel">
                                           <li class="header_link">
                                            <?= i18n('adminpanel') ?>
                                            </li>
                                            </a>
                    <?php endif ?>
                </ul>
            </div>
        </header>
        <div id="posts">
            <?php
            $resultsPerPage = 10;

            if (!isset($_GET['page']))
                $page = 1;
            else
                $page = $_GET['page'];

            $pageFirstResult = number_format(($page - 1) * $resultsPerPage);

            // @TODO: bind params somehow
            $result       = $mysqli->query("SELECT * FROM posts ORDER BY postcreated DESC LIMIT " . sanitize($pageFirstResult) . ',' . sanitize($resultsPerPage));
            $resultNumber = $result->num_rows;
            $pageNumber   = ceil($resultNumber / $resultsPerPage);

            if ($result->num_rows < 1)
                echo "<p class=\"zeroposts_msg\">" . i18n("zeroposts") . "<br><a href=\"/admpanel\">" . i18n("addpost") . " </a></p>";

            $postAuthor = base64_decode($blog['author']);

            while ($row = $result->fetch_assoc()) {
                // reducing the content of posts if it exceeds 400 characters
            
                $postcontent = strlen($row["postcontent"]) > 400 ? 
                    substr($row["postcontent"], 0, 400) . "..." : $row["postcontent"];

                if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) { // if you're signed in, the "manage post" button appears
                    echo "<div class=\"post\">
                    <div class=\"post_head\">
                    <h1 class=\"post_heading\">{$row["postname"]}</h1>
                    <p class=\"post_subheading\"> " . i18n('by') . " <b>{$postAuthor}</b> 
                    " . i18n('at') . " {$row["postcreated"]}</p>
                    </div>
                    <div class=\"post_text\">{$postcontent}</div>
                    <div class=\"btns\">
                    <a href=\"/view/?id={$row['postid']}\">
                    <button class=\"btn-blue\" style=\"margin-bottom: 5px; margin-right: 1em;\">"
                        . i18n('contreading') . "
                     </button></a>
                     <a href=\"/repoxy/modules/deletePost.php?postid={$row['postid']}&cleanOutput=true\">
                     <button class=\"btn-red\">" . i18n('delete') . "</button>
                     </a>
                     </div>
                     </div>";
                } else {
                    echo "<div class=\"post\">
                    <div class=\"post_head\">
                    <h1 class=\"post_heading\">{$row["postname"]}</h1>
                    <p class=\"post_subheading\"> 
                    " . i18n('by') . " <b>{$postAuthor}</b> 
                    " . i18n('at') . " {$row["postcreated"]}</p>
                    </div>
                    <div class=\"post_text\">{$postcontent}</div>
                    <div class=\"btns\">
                    <a href=\"/view/?id={$row['postid']}\">
                    <button class=\"btn-blue\">
                    " . i18n('contreading') . "
                    </button>
                    </a>
                    </div>
                    </div>";
                }
            }

            $mysqli->close();

            for ($page = 1; $page <= $pageNumber; $page++)
                echo "<a href=\"?page=$page\" class=\"pagelinks\">$page</a>";
            ?>
        </div>
    </main>
</body>

</html>