<?php

$path = $_REQUEST['path'];
if ($path == "" || $path == null || $path == "/index.php") {
  $path = "/";
}

//echo $path;

require 'vendor/autoload.php';

require_once 'controller/PagesController.php';
require_once 'controller/StoreController.php';
require_once 'controller/DebugController.php';
require_once 'controller/ErrorController.php';

use Pux\Executor;

$mux = new Pux\Mux;
$mux->mount("/store", new StoreController());
$mux->mount("/debug", new DebugController());
$mux->mount("/error", new ErrorController());
$mux->mount("", new PagesController());

try {
  $route = $mux->dispatch( $path );
  echo Executor::execute($route);
} catch (Error $e) {
  try {
    $route = $mux->dispatch( "$path/" );
    echo Executor::execute($route);
  } catch (Error $ee) {
    $route = $mux->dispatch( '/error/404' );
    echo Executor::execute($route);
  }
}
