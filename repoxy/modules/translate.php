<?php
function i18n(string $token)
{
    $blogcfg = "{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini";
    $stringsFile = file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/i18.json");
    $strings = json_decode($stringsFile, true);
    $lang = base64_decode(parse_ini_file($blogcfg)['lang']);

    return $strings[$lang][$token] ?? $token;
}

