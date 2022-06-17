<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sendjson.php");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");

error_reporting(E_ERROR | E_PARSE);
session_start();

// checks if admin's data is valid
if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) {
    if (isset($_GET['postcontent']) && isset($_GET['postname'])) {
        // connecting to database
        try {
            $mysqli = new mysqli($rpxycfg['server'], $rpxycfg['user'], $rpxycfg['psw'], $rpxycfg['dbname']);
            $stmt = $mysqli->prepare("INSERT INTO posts (postid, postname, postcontent, postcreatedAt) VALUES (?, ?, ?, ?)");

            // send request in database
            $stmt->bind_param("ssss", rand(1, 999999), strip_tags($_GET['postname']), strip_tags($_GET['postcontent']), strip_tags($_GET['postcreation']));
            $stmt->execute(); // sending
            $mysqli->close(); //  closing the connection

            echo sendjson(["msg" => "OK"]); // notifying client what posting is complete
        } catch (\Throwable $th) { // if some error, sending to client
            http_response_code(500);
            echo sendjson(["msg" => $th->getMessage()]);
        }
    } else { // if some of fields is empty
        http_response_code(500);
        echo sendjson(["msg" => "Post content or post name is empty."]);
    }
} else { // if not valid admin's data
    http_response_code(403);
    session_unset();
    session_destroy();
    echo sendjson(["msg" => "Not allowed."]);
}
