<?php
require_once("{$_SERVER['DOCUMENT_ROOT']}/repoxy/modules/layout.php");

session_start();

$lt = new Layout();
$lt->loadpage('viewpost', 'default');