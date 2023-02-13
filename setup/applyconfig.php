<?php
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/writeini.php");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/updateini.php");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sendjson.php");
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/sanitize.php");

$configFile = "{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini";
$rpxyConfig = "{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini";

$cfg = [
    "blog" => [
        "name" => base64_encode($_GET['bname']),
        "desc" => base64_encode($_GET['bdesc']),
        "author" => base64_encode($_GET['bauthor']),
        "layout" => base64_encode($_GET['blayout']),
        "authorpsw" => base64_encode($_GET['blauthorpsw']),
        "creation" => base64_encode($_GET['installDate']),
        "lang" => base64_encode($_GET['blang'])
    ],
    "contacts" => [
        "email" => base64_encode($_GET['bemail']),
        "twitter" => base64_encode($_GET['btw']),
        "facebook" => base64_encode($_GET['bfb']),
        "reddit" => base64_encode($_GET['brt']),
        "discord" => base64_encode($_GET['bds'])
    ]
];

$dbcfg = [
    "repoxydb" => [
        'psw' => base64_encode($_GET['dbpsw']),
        'user' => base64_encode($_GET['dbuser']),
        'dbname' => base64_encode($_GET['dbname']),
        'server' => base64_encode($_GET['dbhost'])
    ],
    // please don't touch line below, and not add "by Design". original author is marshallovski.
    // github: https://github.com/marshallovski/repoxy/
    'repoxy' => ['installed' => 'true', 'version' => '1.0']
];


try {
    $mysqli = new mysqli(base64_decode($dbcfg['repoxydb']['server']), base64_decode($dbcfg['repoxydb']['user']), base64_decode($dbcfg['repoxydb']['psw']));

    // i think i'm need to place this code everywhere else
    if (!$mysqli) {
        http_response_code(500);
        echo sendjson(["msg" => "Couldn't connect to database: {$mysqli->connect_error}. {$mysqli->error}"]);
    }

    // https://github.com/marshallovski/repoxy/issues/1

    // @FIX: this is NOT a SQL injection. the code below just creates a database. 
    // i checked it, and if we creating a new database named "DROP DATABASE `test`" 
    // the code below simply creates a new database named "drop database test".
    // MUST be fixed in (( near )) future
    $mysqli->real_query(sanitize(mysqli_real_escape_string($mysqli, "CREATE DATABASE IF NOT EXISTS `" . base64_decode($dbcfg['repoxydb']['dbname']) . "`")));

    // select our database to create table for posts
    // @TODO: use "template string" instead of "`". $var . "`"
    $mysqli->real_query(sanitize(mysqli_real_escape_string($mysqli, "USE `" . base64_decode($dbcfg['repoxydb']['dbname']) . "`")));

    // creating a table for posts
    $query = "CREATE TABLE IF NOT EXISTS `posts` (
        `postid` int(6) UNSIGNED NOT NULL,
        `postname` varchar(48) NOT NULL,
        `postcontent` varchar(4000) NOT NULL,
        `postcreated` varchar(30) NOT NULL 
      )";

    // writing config to config file (/repoxy/misc/blog_config.ini)
    write_ini_file($cfg, $configFile, true);

    // tells Repoxy it's properly installed & configured (@TODO: should remove this, it's never used)
    write_ini_file($dbcfg, $rpxyConfig, true);

    $mysqli->query($query);

    // removing all posts
    if (isset($_GET['resetdb']) && $_GET['resetdb'] === 'true')
        $mysqli->query("DELETE FROM `posts`;");

    $mysqli->close(); // closing connection to DB

    // deleting setup folder
    // @FIX: this deletes only files, not folders
    // code from https://intecsols.com/delete-files-and-folders-from-a-folder-using-php-by-intecsols/
    if (isset($_GET['deleteSetup']) && $_GET['deleteSetup'] === 'true') {
        $folder = '../setup';

        // Get a list of all of the file names in the folder.
        $files = glob("{$folder}/*");

        // Loop through the file list.
        foreach ($files as $file) {
            // Make sure that this is a file and not a directory.
            if (is_file($file)) {
                // Use the unlink function to delete the file.
                unlink($file);
            }
        }

        // rmdir('../setup'); // doesn't works
    }

    // notifying client installing is complete
    http_response_code(200);
    echo sendjson(["msg" => "OK"]);
} catch (\Throwable $th) {
    http_response_code(500);
    echo sendjson(["msg" => "Error: {$th->getMessage()}"]);
}