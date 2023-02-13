<?php
$blog    = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");

require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/translate.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/mlog.php");
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
?>

<!DOCTYPE html>
<html lang="<?= base64_decode($blog['lang']) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?= i18n('adminpanel') ?> <?= base64_decode($blog['name']) ?>
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
                    <li class="header_link" id="haddpostBtn">
                        <?= i18n('addpost') ?>
                    </li>
                    <li class="header_link" id="heditpostBtn">
                        <?= i18n('mngposts') ?>
                    </li>
                    <li class="header_link" id="hchlangBtn">
                        <?= i18n('changelang') ?>
                    </li>
                </ul>
            </div>
        </header>
        <div class="loginform loginform_large">
            <h2>
                <?= i18n('adminpanel') ?>
            </h2>
            <div class="btns">
                <details id="addpost">
                    <summary><?= i18n('addpost') ?></summary>
                    <label for="postName">
                        <?= i18n('postname') ?>
                    </label>
                    <input type="text" name="postname" id="addpostname" minlength="2" maxlength="48"
                        placeholder="Hello World!" required autocomplete="off">
                    <br>
                    <br>
                    <span>
                        <?= i18n('postcontent') ?>
                    </span>
                    <div id="posteditor"></div>
                    <br>
                    <button class="btn-green" id="addpostBtn"><?= i18n('save') ?></button>
                </details>

                <details id="editpost">
                    <summary>
                        <?= i18n('mngposts') ?>
                    </summary>
                    <?php
                    $resultsPerPage = 10;

                    if (!isset($_GET['page']))
                        $page = 1;
                    else
                        $page = number_format($_GET['page']);

                    $pageFirstResult = ($page - 1) * $resultsPerPage;

                    // @TODO: bind_params here
                    $query        = "SELECT * FROM `posts` ORDER BY `postcreated` DESC LIMIT {$pageFirstResult}, {$resultsPerPage};";
                    $result       = $mysqli->query($query);
                    $resultNumber = $result->num_rows;
                    $pageNumber   = ceil($resultNumber / $resultsPerPage);

                    if ($result->num_rows < 1)
                        echo i18n("zeroposts");

                    while ($row = $result->fetch_assoc()) {
                        echo "<div class=\"post_preview\" id=\"post_{$row['postid']}\">
                            <input type=\"text\" name=\"postname\" id=\"editpname_{$row['postid']}\" value=\"{$row["postname"]}\" class=\"postname_inp\" autocomplete=\"off\">
                            <p>" . i18n('at') . " {$row["postcreated"]}, ID: {$row['postid']}</p>
                            <textarea class=\"post_preview_postcontent\" autocomplete=\"off\" id=\"editptxt_{$row['postid']}\">{$row["postcontent"]}</textarea>
                            <br>
                            <br>
                            <button class=\"btn-green\" onclick=\"editPost('{$row['postid']}');\">" . i18n('save') . "</button>
                            <button class=\"btn-red\" onclick=\"deletePost('{$row['postid']}');\">" . i18n('delete') . "</button>
                        </div>";
                    }

                    for ($page = 1; $page <= $pageNumber; $page++)
                        echo '<a href="?page=' . $page . '">' . $page . ' </a>';
                    ?>
                </details>

                <details>
                    <summary>
                        <?= i18n('mngpages') ?>
                    </summary>
                    soon
                </details>

                <!-- <details>
                    <summary>
                        <?= i18n('mngcomments') ?>
                    </summary>
                    soon
                </details> -->

                <details>
                    <summary><?= i18n('mnglayout') ?></summary>
                    soon
                </details>

                <details id="chlang">
                    <summary>
                        <?= i18n('changelang') ?>
                    </summary>
                    <select id="bloglang">
                        <?php
                        $stringsFile = file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/i18.json");
                        $strings     = json_decode($stringsFile, true);

                        foreach ($strings as $lang => $value)
                            echo "<option value=\"$lang\">{$lang}</option>";
                        ?>
                    </select>
                    <br>
                    <br>
                    <button class="btn-green" onclick="changeLang($('id', 'bloglang').value)">
                        <?= i18n('save') ?>
                    </button>
                </details>
            </div>
            <button class="btn-red" id="logoutBtn"><?= i18n("logout") ?></button>
        </div>
    </main>
    <script src="/repoxy/layouts/default/js/ckeditor.js"></script>
    <script src="/repoxy/layouts/default/js/swal.js"></script>
    <script src="/repoxy/layouts/default/js/admpanel.js"></script>
</body>

</html>