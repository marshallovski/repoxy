<?php
$blog = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sanitize.php");

if (isset($_POST['username']) && isset($_POST['userpsw'])) {
    if ($_POST['userpsw'] === base64_decode($blog['authorpsw']) && $_POST['username'] === base64_decode($blog['author'])) {
        session_start();

        $_SESSION['username'] = sanitize($_POST['username']);
        $_SESSION['userpsw']  = sanitize($_POST['userpsw']);

        header('Location: /admpanel');
    } else {
        header('Location: /admlogin/?fail');
    }
} else {
    header('Location: /admlogin');
}