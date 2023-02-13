<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/layout.php");
$blogcfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");

session_start();
// checks if admin's data is valid
if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) {
  // redirecting to admin panel
  header('Location: /admpanel');
} else { // not valid admin's data:
  // loading login page
  $lt = new Layout();
  $lt->loadpage('admlogin', base64_decode($blogcfg['layout'])); // loading page "admlogin" (layouts will be in /repoxy/layouts/)
}
