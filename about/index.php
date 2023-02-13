<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/layout.php");
$blogcfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");

session_start();

// loading about page from default layout (/repoxy/misc/blog_config.ini -> "layout" (must be base64 encoded))
$lt = new Layout();
$lt->loadpage('about', base64_decode($blogcfg['layout'])); // loading page "about" (layouts will be in /repoxy/layouts/)
