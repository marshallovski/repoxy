<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Repoxy Installer</title>
    <link rel="stylesheet" href="/setup/css/main.css">
    <link rel="icon" href="/setup/assets/logo.png">
    <meta name="robots" content="noindex">
</head>

<body>
    <noscript>
        <style>
            main {
                display: none;
            }

            h1 {
                text-align: center;
            }
        </style>
        <h1>Please enable JavaScript in your browser!</h1>
    </noscript>

    <main>
        <div id="inst_welcome">
            <img src="/setup/assets/logo.png" class="logo">
            <h1>
                😃
                Welcome to Repoxy Installer!
            </h1>
            <p class="subheading">
                Installer will install Repoxy
                <b>
                    <?= parse_ini_file("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/repoxy.ini")['version'] ?>
                </b>
            </p>
            <div class="btns">
                <button class="btn-red" id="wl_clinstall">
                    Cancel
                </button>
                <button class="btn-green" id="wl_continue">
                    Next
                </button>
            </div>
        </div>

        <div id="inst_cfg">
            <h1>Configure your blog</h1>
            <p class="subheading" style="margin-bottom: 3em">
                Just fill blog's info below. Make sure what you have a <b>MySQL/MariaDB</b>.
            </p>

            <div class="options">
                <h2>
                    Blog template
                </h2>
                <?php
        $files = scandir("{$_SERVER['DOCUMENT_ROOT']}/repoxy/layouts/", 0);

        for ($i = 0; $i < count($files); $i++) {
            if ($files[$i] === '.' || $files[$i] === '..') {
                continue;
            } else {
                echo "<p id=\"opt_layout\" class=\"layoutname\">{$files[$i]}</p><br>";
            }
        }
        ?>

                <h2 class="option_heading">
                    Blog properties
                </h2>

                <p>
                    Blog author (minimum 3 characters)
                </p>
                <input type="text" id="opt_blauthor" minlength="3" maxlength="20" autocomplete="off" required>
                <br>
                <br>


                <p>
                    Blog author's password
                </p>
                <input type="password" id="opt_blauthorpsw" maxlength="64" autocomplete="off" required>
                <br>
                <br>

                <p>
                    Blog name
                </p>
                <input type="text" id="opt_blname" maxlength="26" required>
                <br>
                <br>
                <p>
                    Blog description
                </p>
                <textarea id="opt_bldesc" maxlength="500" class="bldesc_text"></textarea>
                <br>
                <br>
                <p>Blog language</p>
                <select autocomplete="off" id="opt_blang">
                    <?php
           $stringsFile = file_get_contents("{$_SERVER['DOCUMENT_ROOT']}/repoxy/misc/i18.json");
           $strings = json_decode($stringsFile, true);

           foreach ($strings as $lang => $value)
               echo "<option value=\"{$lang}\">{$lang}</option>";
           ?>
                </select>
                <br>
                <br>
                <h2>Author's contacts</h2>
                <p class="subheading">
                    If value is empty - it's will ignored
                </p>
                <br>
                <p>
                    Email
                </p>
                <input type="email" id="opt_blemail" placeholder="mail@coolmail.org" autocomplete="off">
                <br>
                <br>
                <p>
                    Twitter
                </p>
                <input type="text" placeholder="@cooluser" autocomplete="off" id="opt_bltw">
                <br>
                <br>
                <p>
                    Facebook
                </p>
                <input type="text" placeholder="@cooluser" autocomplete="off" id="opt_blfb" autocomplete="off">
                <br>
                <br>
                <p>
                    Reddit
                </p>
                <input type="url" id="opt_blreddit" placeholder="https://reddit.com/r/economy" autocomplete="off">
                <br>
                <br>
                <p>
                    Discord
                </p>
                <input type="url" placeholder="https://discord.gg/W3W889yX" id="opt_blds" autocomplete="off">
                <h2 class="option_heading">
                    Database settings
                </h2>
                <p class="subheading">Make sure what you have <b>MySQL </b> or <b>MariaDB</b>. Other databases <b>isn't
                        supported!</b></p>
                <br>
                <p>
                    Database password (<b>32 chars. max.</b>)
                </p>
                <input type="password" placeholder="12345ab" autocomplete="off" id="opt_bldbpsw" autocomplete="off"
                    maxlength="32" style="width: 250px;">
                <br>
                <br>
                <p>
                    Database name (<b>18 chars. max.</b>)
                </p>
                <input type="text" placeholder="repoxydb" autocomplete="off" id="opt_bldbname" autocomplete="off"
                    maxlength="18" value="repoxydb">
                <br>
                <br>
                <p>
                    Database user (<b>18 chars. max.</b>)
                </p>
                <input type="text" placeholder="root" autocomplete="off" id="opt_bldbuser" autocomplete="off"
                    maxlength="18">
                <br>
                <br>
                <p>
                    Database port
                </p>
                <input type="text" placeholder="3306" autocomplete="off" id="opt_bldbport" autocomplete="off"
                    maxlength="4" value="3306">
                <br>
                <br>
                <p>
                    Database host
                </p>
                <input type="text" placeholder="localhost" autocomplete="off" id="opt_bldbhost" autocomplete="off"
                    value="localhost">
                <br>
                <br>
                <input type="checkbox" name="resetdb" id="opt_resetdb" style="width: auto;" autocomplete="off">
                <label for="resetdb">Reset Repoxy database (will delete all old posts)</label>
                <br>
                <input type="checkbox" name="delsetup" id="opt_delsetup" style="width: auto;" autocomplete="off" checked>
                <label for="delsetup">Delete installation files <b>(recommended)</b></label>
                <div class="btns">
                    <button class="btn-green" id="installBtn" style="display: block; margin: auto;"
                        type="submit">Install</button>
                </div>
            </div>
        </div>
    </main>
    <script src="/setup/js/main.js"></script>
</body>

</html>