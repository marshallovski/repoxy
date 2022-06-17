<?php
//$rpxycfg = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini");
$blog = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini");

switch ($_GET['what']) {
    case 'login':
        if (isset($_POST['username']) && isset($_POST['userpsw'])) {
            if ($_POST['userpsw'] === $blog['authorpsw'] && $_POST['username'] === $blog['author']) {
                session_start();
                $_SESSION['username'] = htmlspecialchars($_POST['username']);
                $_SESSION['userpsw'] = htmlspecialchars($_POST['userpsw']);
                header('Location: /admpanel');
            } else {
                header('Location: /admlogin/?msg=fail');
            }
        } else {
            header('Location: /admlogin');
        }
        break;

    default:
        return mlog('Please enter valid method.');
        break;
}
