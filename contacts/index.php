<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/layout.php");
$blogcfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");

session_start();

$lt = new Layout();
$lt->loadpage('contacts', base64_decode($blogcfg['layout']));