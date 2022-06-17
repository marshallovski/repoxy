<?php
function loadmodule($libname)
{
  if (file_exists("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/{$libname}.php")) {
    require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/{$libname}.php");
  } else {
    echo '<br>Failed load module: ' . $libname;
  }
}

// loading modules
loadmodule('layout');

// loading default page
$lt = new Layout();
$lt->loadpage('home', 'default'); // loading page "home" from layout "default" (layouts will be in /repoxy/layouts/)
