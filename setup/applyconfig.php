<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/writeini.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/updateini.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sendjson.php");

$configFile = "{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini";
$rpxyConfig = "{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini";

$cfg = [
    "blog" => [
        "name" => $_GET['bname'],
        "desc" => $_GET['bdesc'],
        "author" => $_GET['bauthor'],
        "layout" => $_GET['blayout'],
        "authorpsw" => $_GET['blauthorpsw'],
        "creation" => $_GET['installDate']
    ],
    "contacts" => [
        "email" => $_GET['bemail'],
        "twitter" => $_GET['btw'],
        "facebook" => $_GET['bfb'],
        "reddit" => $_GET['brt'],
        "discord" => $_GET['bds']
    ]
];

$dbcfg = [
    "repoxydb" => [
        'psw' => $_GET['dbpsw'],
        'user' => $_GET['dbuser'],
        'dbname' => $_GET['dbname'],
        'server' => $_GET['dbhost']
    ],
    'repoxy' => ['installed' => 'true', 'version' => '0.0.4'] // please don't touch 'version'
];

try {
    // writing to config (/repoxy/misc/blog_config.ini)
    write_ini_file($cfg, $configFile, true);

    // tells Repoxy what it's properly installed & configured
    write_ini_file($dbcfg, $rpxyConfig, true);

    $mysqli = new mysqli(parse_ini_file($rpxyConfig)['server'], parse_ini_file($rpxyConfig)['user'], parse_ini_file($rpxyConfig)['psw'], parse_ini_file($rpxyConfig)['dbname']);
    $query = "CREATE TABLE `posts` (
      `postid` int(6) UNSIGNED NOT NULL,
      `postname` varchar(48) NOT NULL,
      `postcontent` varchar(4000) NOT NULL,
      `postcreatedAt` varchar(30) NOT NULL
    )";

    $mysqli->query($query);
    $mysqli->close(); // closing connection to DB

    if (!$mysqli) {
        http_response_code(500);
        echo sendjson(["msg" => "Could not connect to DB: {$mysqli->connect_error}. {$mysqli->error}"]);
    } else {
        // responding in JSON and notifying client what installing is complete
        $data = ["msg" => "OK"];
        header('Content-Type: application/json; charset=utf-8;');
        http_response_code(200);
        echo json_encode($data);
    }
} catch (\Throwable $th) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8;');
    $errdata = ["msg" => "Error: {$th->getMessage()}"];
    echo json_encode($errdata);
}
