<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sendjson.php");
$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");

error_reporting(E_ERROR | E_PARSE);
session_start();

// check if admin's data is valid
if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) {
    if (isset($_GET['postcontent']) && isset($_GET['postname'])) {
        $postid      = rand(1, 999999); // random post ID, 6 digits (accessible via /view/?post=000000)
        $postname    = strip_tags($_GET['postname']);
        $postcontent = $_GET['postcontent'];
        $postcreated = strip_tags($_GET['postcreation']);

        // connecting to database
        try {
            $mysqli = new mysqli(
                base64_decode($rpxycfg['server']),
                base64_decode($rpxycfg['user']),
                base64_decode($rpxycfg['psw']),
                base64_decode($rpxycfg['dbname'])
            );

            $stmt = $mysqli->prepare("INSERT INTO posts (postid, postname, postcontent, postcreated) VALUES (?, ?, ?, ?)");

            // sending request in database
            $stmt->bind_param("ssss", $postid, $postname, $postcontent, $postcreated);
            $stmt->execute(); // sending
            $mysqli->close();

            echo sendjson(["msg" => "OK"]); // notifying client posting is complete
        } catch (\Throwable $th) { // if some error, sending to client
            http_response_code(500);
            echo sendjson(["msg" => $th->getMessage()]);
        }
    } else { // if some of fields is empty
        http_response_code(500);
        echo sendjson(["msg" => "Post content or post name is empty."]);
    }
} else { // if not valid admin's data or not logined in
    http_response_code(403);
    session_unset();
    session_destroy();
    echo sendjson(["msg" => "Not allowed"]);
}