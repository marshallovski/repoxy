<?php
$blog = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");

require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/translate.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $blog['name'] ?> - <?= i18('contacts', 'auto') ?></title>

    <link rel="stylesheet" href="/repoxy/layouts/default/css/base.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/common.css">

    <!-- Buttons -->
    <link rel="stylesheet" href="/repoxy/layouts/default/css/buttons.css">
    <link rel="icon" href="/repoxy/layouts/default/assets/logo.png" type="image/png">


    <meta name="title" content="<?= $blog['name'] ?>">
    <meta name="description" content="<?= i18('contacts', 'auto') ?>">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $url ?>">
    <meta property="og:title" content="<?= $blog['name'] ?>">
    <meta property="og:description" content="<?= i18('contacts', 'auto') ?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $url ?>">
    <meta property="twitter:title" content="<?= $blog['name'] ?>">
    <meta property="twitter:description" content="<?= i18('contacts', 'auto') ?>">
    <meta name="generator" content="Repoxy <?= $rpxycfg['version'] ?>" />
</head>

<body>
    <main class="common-container">
        <h1><?= i18('contacts', 'auto') ?></h1>
        <br>
        <?php 
            if ($blog['email'] !== '') {
                echo "<p><b>Email:</b> {$blog['email']}</p>";
            }

            if ($blog['twitter'] !== '') {
                echo "<p><b>Twitter:</b> <a href=\"twitter.com/{$blog['twitter']}\">{$blog['twitter']}</a></p>";
            }

            if ($blog['facebook'] !== '') {
                echo "<p><b>Facebook:</b> <a href=\"facebook.com/{$blog['facebook']}\">{$blog['facebook']}</a></p>";
            }

            if ($blog['reddit'] !== '') {
                echo "<p><b>Reddit:</b> <a href=\"{$blog['reddit']}\">".str_replace('https://reddit.com/', ' ', $blog['reddit'])."</a></p>";
            }

            if ($blog['discord'] !== '') {
                echo "<p><b>Discord:</b> <a href=\"{$blog['discord']}\">".str_replace('https://discord.gg/', ' ', $blog['discord'])."</a></p>";
            }
        ?>
    </main>
</body>

</html>