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
    <title><?= base64_decode($blog['name']) ?> - <?= i18n('about') ?></title>

    <link rel="stylesheet" href="/repoxy/layouts/default/css/base.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/common.css">

    <link rel="stylesheet" href="/repoxy/layouts/default/css/buttons.css">
    <link rel="icon" href="/repoxy/layouts/default/assets/logo.png" type="image/png">

    <meta name="title" content="<?= base64_decode($blog['name']) ?>">
    <meta name="description" content="<?= i18n('about') ?> <?= base64_decode($blog['name']) ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= base64_decode($blog['name']) ?>">
    <meta property="og:description" content="<?= i18n('about') ?> <?= base64_decode($blog['name']) ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="<?= base64_decode($blog['name']) ?>">
    <meta property="twitter:description" content="<?= i18n('about') ?> <?= base64_decode($blog['name']) ?>">
    <meta name="generator" content="Repoxy <?= $rpxycfg['version'] ?>" />
</head>

<body>
    <main class="common-container">
        <h1><?= i18n('about') ?> "<?= base64_decode($blog['name']) ?>"</h1>
        <br>
        <p class="blogdesc"><?= base64_decode($blog['desc']) ?></p>
        <a href="/"><button><?= i18n('tohomepage') ?></button> </a>

        <p class="subheading">
            <a href="https://github.com/marshallovski/repoxy" class="sublink">
                <?= i18n('poweredby') ?> <b><?= $rpxycfg['version'] ?> —</b>
            </a> 
            <?= i18n('ossengine') ?>
            </p>
    </main>
</body>

</html>