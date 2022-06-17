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
session_start();

// checks if admin's data is valid
if (isset($_SESSION['userpsw']) && isset($_SESSION['username'])) {
  // redirecting to admin panel
  header('Location: /admpanel');
} else { // not valid admin's data:
  // loading login page
  $lt = new Layout();
  $lt->loadpage('admlogin', 'default'); // loading page "login" from layout "default" (layouts will be in /repoxy/layouts/)
}
