<?php
$blog = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");

require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/translate.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/mlog.php");

header('X-XSS-Protection "1; mode=block"');

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
        <?= i18('adminpanel', 'sess') ?> - <?= $blog['name'] ?>
    </title>
    <link rel="stylesheet" href="/repoxy/layouts/default/css/base.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/admpanel.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/buttons.css">

    <link rel="icon" href="/repoxy/layouts/default/assets/logo.png" type="image/png">

    <meta name="robots" content="noindex">
</head>

<body>
    <noscript>
        <style>
            main {
                display: none;
            }
        </style>
        <h1>Please turn on JavaScript in your browser.</h1>
    </noscript>

    <main class="adminpanel">
        <header>
            <h1 class="blogname"><?= $blog['name'] ?></h1>
            <div class="header_links">
                <ul>
                    <a href="/">
                        <li class="header_link link_active">
                            <?= i18('homepage', 'sess') ?>
                        </li>
                    </a>
                    <li class="header_link" id="haddpostBtn">
                        <?= i18('addpost', 'sess') ?>
                    </li>
                    <li class="header_link" id="heditpostBtn">
                    <?= i18('mngposts', 'sess') ?>
                    </li>
                    <li class="header_link" id="hchlangBtn">
                        <?= i18('changelang', 'sess') ?>
                    </li>
                </ul>
            </div>
        </header>
        <div class="loginform loginform_large">
            <h2><?= i18('adminpanel', 'sess') ?></h2>
            <div class="btns">
                <details id="addpost">
                    <summary><?= i18('addpost', 'sess') ?></summary>
                    <label for="postName"><?= i18('postname', 'sess') ?></label>
                    <input type="text" name="postname" id="addpostname" minlength="2" maxlength="48" placeholder="Hello World!" required autocomplete="off">
                    <br>
                    <br>
                    <span><?= i18('postcontent', 'sess') ?></span>
                    <div id="posteditor"></div>
                    <br>
                    <button class="btn-green" id="addpostBtn"><?= i18('save', 'sess') ?></button>
                </details>

                <details id="editpost">
                    <summary><?= i18('mngposts', 'sess') ?></summary>
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

                    $query = "SELECT * FROM `posts` ORDER BY `postcreatedAt` DESC LIMIT " . $page_first_result . ',' . $results_per_page;
                    $result = $mysqli->query($query);

                    while ($row = $result->fetch_assoc()) {
                        echo "<div class=\"post_preview\" id=\"post_{$row['postid']}\">
                            <input type=\"text\" name=\"postname\" id=\"editpname_{$row['postid']}\" value=\"{$row["postname"]}\" class=\"postname_inp\" autocomplete=\"off\">
                            <p>" . i18('at', 'sess') . " {$row["postcreatedAt"]}, ID: {$row['postid']}</p>
                            <textarea class=\"post_preview_postcontent\" autocomplete=\"off\" id=\"editptxt_{$row['postid']}\">{$row["postcontent"]}</textarea>
                            <br>
                            <br>
                            <button class=\"btn-green\" onclick=\"editPost('{$row['postid']}');\">" . i18('save', 'sess') . "</button>
                            <button class=\"btn-red\" onclick=\"deletePost('{$row['postid']}');\">" . i18('delete', 'sess') . "</button>
                        </div>";
                    }

                    for ($page = 1; $page <= $number_of_page; $page++) {
                        echo '<a href="?page=' . $page . '">' . $page . ' </a>';
                    }
                    ?>
                </details>

                <details>
                    <summary><?= i18('mngpages', 'sess') ?></summary>
                    soon
                </details>

                <details>
                    <summary><?= i18('mngcomments', 'sess') ?></summary>
                    soon
                </details>

                <details>
                    <summary><?= i18('mnglayout', 'sess') ?></summary>
                    soon
                </details>

                <details id="chlang">
                    <summary><?= i18('changelang', 'sess') ?></summary>
                    <select id="bloglang">
                        <option value="en">
                            English
                        </option>
                        <option value="ru">
                            Русский (Russian)
                        </option>
                        <option value="uk">
                            Українська (Ukrainian)
                        </option>
                    </select>
                    <br>
                    <br>
                    <button class="btn-green" onclick="changeLang($('id', 'bloglang').value)"><?= i18('save', 'sess') ?></button>
                </details>
            </div>
        </div>
    </main>
    <script src="/repoxy/layouts/default/js/ckeditor.js"></script>
    <script src="/repoxy/layouts/default/js/swal.js"></script>
    <script src="/repoxy/layouts/default/js/admpanel.js"></script>
</body>

</html>