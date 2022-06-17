<?php
function i18(string $token, string $lang)
{
    $stringsFile = file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/i18.json");
    $jsontr = json_decode($stringsFile, true);

    if (isset($_SESSION['lang']) && $lang === 'sess') {
        return $jsontr[$_SESSION['lang']][$token];
    } else if (!isset($_SESSION['lang'])) {
        $autolang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        return $jsontr[$autolang][$token];
    } else {
        return $jsontr[$lang][$token];
    }
}

