<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/layout.php");
$blogcfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");

// loading default page
$lt = new Layout();
$lt->loadpage('home', base64_decode($blogcfg['layout'])); // loading page "home" (layouts will be in /repoxy/layouts/)
