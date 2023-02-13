<?php
$blog = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/translate.php");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/httptohttps.php");

httptohttps();
?>

<!DOCTYPE html>
<html lang="<?= base64_decode($blog['lang']) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?= i18n('login') ?> - <?= base64_decode($blog['name']) ?>
    </title>
    <link rel="stylesheet" href="/repoxy/layouts/default/css/base.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/admpanel.css">

    <link rel="stylesheet" href="/repoxy/layouts/default/css/buttons.css">
    <link rel="icon" href="/repoxy/layouts/default/assets/logo.png" type="image/png">

    <meta name="robots" content="noindex">

</head>

<body>
    <main class="loginform">
        <h2><?= i18n('admpanellogin') ?></h2>
        <br>
        <?php
        if (isset($_GET['fail']))
            echo "<div class=\"error\">&#9888;&nbsp;" . i18n('crednotmatch') . "</div>";
        ?>
        <form action="/repoxy/modules/login.php" method="post">
            <label for="username"><?= i18n('username') ?>:</label>
            <input type="text" name="username" required autocomplete="off">
            <br>
            <br>
            <label for="userpsw">
                <?= i18n('psw') ?>:
            </label>
            <input type="password" name="userpsw" required autocomplete="off">
            <div class="btns">
                <button type="submit" class="btn-green">
                    <?= i18n('login') ?>
                </button>
            </div>
        </form>
    </main>
</body>

</html>