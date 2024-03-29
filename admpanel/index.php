<?php
$blogcfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");

function loadmodule($libname)
{
    if (file_exists("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/{$libname}.php")) {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/{$libname}.php");
    } else {
        echo "<br>Failed load module: " . $libname;
    }
}

loadmodule('layout');
loadmodule('mlog');
session_start();

// checks if admin's data is valid
if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) {
    // loading admin panel page
    $lt = new Layout();
    $lt->loadpage('admpanel', base64_decode($blogcfg['layout'])); // loading page "admpanel" from layout "default" (layouts will be in /repoxy/layouts/)
} else { // not valid admin's data, redirecting to login page
    header('Location: /admlogin/?fail');
}
