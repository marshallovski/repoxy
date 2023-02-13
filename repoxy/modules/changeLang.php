<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sendjson.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/updateini.php");
$blogcfg = "{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini";

session_start();
//checks if admin's data is valid
if (isset($_GET['lang']) && isset($_SESSION['username']) && isset($_SESSION['userpsw'])) {
    update_ini_file($blogcfg, "lang", base64_encode($_GET['lang']));
    echo sendjson(["msg" => "OK"]);
} else {
    http_response_code(403);
    echo sendjson(["msg" => "Not allowed"]);
}
