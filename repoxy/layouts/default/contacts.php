<?php
$blog = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");

require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/translate.php");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/httptohttps.php");

httptohttps();
?>
<!DOCTYPE html>
<html lang="<?= base64_decode($blog['lang']) ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= base64_decode($blog['name']) ?> - <?= i18n('contacts') ?></title>

    <link rel="stylesheet" href="/repoxy/layouts/default/css/base.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/common.css">

    <link rel="stylesheet" href="/repoxy/layouts/default/css/buttons.css">
    <link rel="icon" href="/repoxy/layouts/default/assets/logo.png" type="image/png">


    <meta name="title" content="<?= base64_decode($blog['name']) ?>">
    <meta name="description" content="<?= i18n('contacts') ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= base64_decode($blog['name']) ?>">
    <meta property="og:description" content="<?= i18n('contacts') ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="<?= base64_decode($blog['name']) ?>">
    <meta property="twitter:description" content="<?= i18n('contacts') ?>">
    <meta name="generator" content="Repoxy <?= $rpxycfg['version'] ?>" />
</head>

<body>
    <main class="common-container">
        <h1><?= i18n('contacts') ?></h1>
        <br>
        <?php
        $blogEmail = base64_decode($blog['email']);
        $blogTwitter = base64_decode($blog['twitter']);
        $blogFb = base64_decode($blog['facebook']);
        $blogReddit = base64_decode($blog['reddit']);
        $blogDiscord = base64_decode($blog['discord']);

            if ($blog['email'] !== '') {
                echo "<p><b>Email:</b> <a href=\"mailto:{$blogEmail}\">{$blogEmail}</a></p>";
            }

            if ($blog['twitter'] !== '') {
                echo "<p><b>Twitter:</b> <a href=\"https://twitter.com/{$blogTwitter}\">{$blogTwitter}</a></p>";
            }

            if ($blog['facebook'] !== '') {
                echo "<p><b>Facebook:</b> <a href=\"https://facebook.com/{$blogFb}\">{$blogFb}</a></p>";
            }

            if ($blog['reddit'] !== '') {
                echo "<p><b>Reddit:</b> <a href=\"https://reddit.com/{$blogReddit}\">".str_replace('https://reddit.com/', ' ', $blogReddit)."</a></p>";
            }

            if ($blog['discord'] !== '') {
                echo "<p><b>Discord:</b> <a href=\"https://discord.gg/{$blogDiscord}\">".str_replace('https://discord.gg/', ' ', $blogDiscord)."</a></p>";
            }
        ?>
    <br>
    <a href="/"><button><?= i18n('tohomepage') ?></button></a>
    </main>
</body>

</html>