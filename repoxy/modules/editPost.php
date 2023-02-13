<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sendjson.php");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");

error_reporting(E_ERROR | E_PARSE);
session_start();

// checks if admin's data is valid
if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) { // @TODO: чекать пароль и юзера с repoxy\misc\repoxy.ini (зачем?)
    if (isset($_GET['postname']) && isset($_GET['postid']) && isset($_GET['postcontent'])) {
        // connecting to database
        try {
            $mysqli = new mysqli(
                base64_decode($rpxycfg['server']),
                base64_decode($rpxycfg['user']),
                base64_decode($rpxycfg['psw']),
                base64_decode($rpxycfg['dbname'])
            );            
            
            $stmt = $mysqli->prepare('UPDATE posts SET postname=?, postcontent=?, postcreated=? WHERE postid=?');
            $stmt->bind_param('ssss', $_GET["postname"], $_GET["postcontent"], $_GET['postupdated'], $_GET["postid"]);

            $stmt->execute(); // sending
            $mysqli->close(); // closing connection to DB

            echo sendjson(["msg" => "OK"]); // notifying client
        } catch (\Throwable $th) { // if some error, sending to client
            http_response_code(500);
            echo sendjson(["msg" => $th->getMessage()]);
        }
    } else { // if some of fields is empty
        http_response_code(500);
        echo sendjson(["msg" => "Post content/post name/post ID is empty."]);
    }
} else { // if not valid admin's data or not logined in
    http_response_code(403);
    session_unset();
    session_destroy();
    echo sendjson(["msg" => "Not allowed"]);
}
