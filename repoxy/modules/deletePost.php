<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sendjson.php");
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sanitize.php");

$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");

error_reporting(E_ERROR | E_PARSE);
session_start();

// checks if admin's data is valid
if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) {
    if (isset($_GET['postid'])) {
        try {
            // connecting to database
            $mysqli = new mysqli(
                base64_decode($rpxycfg['server']),
                base64_decode($rpxycfg['user']),
                base64_decode($rpxycfg['psw']),
                base64_decode($rpxycfg['dbname'])
            );
            
            $stmt = $mysqli->prepare("DELETE FROM posts WHERE postid=?");
            $postid = sanitize($_GET['postid']);

            // sending request in database
            $stmt->bind_param("s", $postid);
            $stmt->execute(); // sending
            $mysqli->close();

            // we musn't show plain JSON to client
            if ($_GET['cleanOutput'] && $_GET['cleanOutput'] === 'true') {
                return header('Location: /');
            } else {
                echo sendjson(["msg" => "OK"]); // notifying client
            }
        } catch (\Throwable $th) { // if some error, sending to client
            http_response_code(500);
            echo sendjson(["msg" => $th->getMessage()]);
        }
    } else { // if some of fields is empty
        http_response_code(403);
        echo sendjson(["msg" => "Post content or post name is empty"]);
    }
} else { // if not valid admin's data or not logined in
    http_response_code(403);
    session_unset();
    session_destroy();
    echo sendjson(["msg" => "Not allowed"]);
}