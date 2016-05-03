<?php

$path = $_REQUEST['path'];
if ($path == "" || $path == null || $path == "/index.php") {
  $path = "/";
}

//echo $path;

require 'vendor/autoload.php';

require_once 'controller/PagesController.php';
require_once 'controller/StoreController.php';

use Pux\Executor;

$mux = new Pux\Mux;
$mux->mount("/store", new StoreController());
$mux->mount("", new PagesController());

$route = $mux->dispatch( $path );
echo Executor::execute($route);
