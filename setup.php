<?php
if (file_exists('./setup') && file_exists('./setup/setupgui.php') && file_exists('./repoxy/misc/blog_config.ini')) {
  include('./setup/setupgui.php');
} else {
  echo "Some files are corrupted, please re-install Repoxy";
}
