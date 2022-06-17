<?php
require("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/mlog.php");

/**
 * @name Layout Manager
 * @description Checks for layout and loads it
 * @path /repoxy/modules/layout.php
 */
class Layout
{
  // available pages: home, viewpost, admpanel, admlogin
  function loadpage(string $page, string $layout)
  {
    if (file_exists("{$_SERVER['DOCUMENT_ROOT']}/repoxy/layouts/{$layout}/{$page}.php")) {
      include("{$_SERVER['DOCUMENT_ROOT']}/repoxy/layouts/{$layout}/{$page}.php");
    } else {
      mlog("<strong>Page/layout not found:</strong> loading page\"{$page}\" from layout \"{$layout}\".");
    }
  }
}
