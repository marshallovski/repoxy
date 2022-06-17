<?php
function loadmodule($libname)
{
    if (file_exists("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/{$libname}.php")) {
        require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/{$libname}.php");
    } else {
        echo '<br>Failed load module: ' . $libname;
    }
}

loadmodule('layout');
session_start();

$lt = new Layout();
$lt->loadpage('about', 'default');
