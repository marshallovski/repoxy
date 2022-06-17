<?php $blog = parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/blog_config.ini"); ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Login - <?= $blog['name'] ?>
    </title>
    <link rel="stylesheet" href="/repoxy/layouts/default/css/base.css">
    <link rel="stylesheet" href="/repoxy/layouts/default/css/admpanel.css">

    <!-- Buttons -->
    <link rel="stylesheet" href="/repoxy/layouts/default/css/buttons.css">
    <link rel="icon" href="/repoxy/layouts/default/assets/logo.png" type="image/png">

    <meta name="robots" content="noindex">

</head>

<body>
    <main class="loginform">
        <?php
        if (isset($_GET['msg']) && $_GET['msg'] === 'fail') {
            echo '<div class="error">&#9888; These credentials do not match our records.</div>';
        }
        ?>
        <form action="/repoxy/modules/check.php?what=login" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required autocomplete="off">
            <br>
            <br>
            <label for="userpsw">Password:</label>
            <input type="password" name="userpsw" required autocomplete="off">
            <div class="btns">
                <button type="submit" class="btn-green">Login</button>
            </div>
        </form>
    </main>
</body>

</html>