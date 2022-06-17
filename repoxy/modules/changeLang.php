<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sendjson.php");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");

session_start();

if (isset($_GET['lang']) && isset($_SESSION['username']) && isset($_SESSION['userpsw'])) {
    $_SESSION['lang'] = $_GET['lang'];
    echo sendjson(["msg" => "OK"]);
} else {
    http_response_code(500);
    echo sendjson(["msg" => "Not allowed to change language."]);
}
