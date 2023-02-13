<?php
if (
    is_dir('../repoxy/') &&
    file_exists('./applyconfig.php') &&
    file_exists('./setupgui.php') &&
    file_exists('../repoxy/misc/blog_config.ini') &&
    is_dir('./assets/') &&
    is_dir('./css/') &&
    is_dir('./js/')
) {
    include('./setupgui.php');
} else {
    http_response_code(500); // @TODO: we needn't this, i think
    // fallback mlog function
    echo "<p style=\"font-family: sans-serif\">Some files are corrupted, please re-install Repoxy.</p>";
}