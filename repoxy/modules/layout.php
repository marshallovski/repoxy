<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/mlog.php");

class Layout
{
  // available pages depends on your template (layout)
  function loadpage(string $page, string $layout)
  {
    $toLoad = "{$_SERVER['DOCUMENT_ROOT']}/repoxy/layouts/{$layout}/{$page}.php";

    if (file_exists($toLoad)) {
      include($toLoad);
    } else {
      http_response_code(500);
      mlog("<strong>Page/layout not found:</strong> loading page \"{$page}\" from layout \"{$layout}\".");
    }
  }
}