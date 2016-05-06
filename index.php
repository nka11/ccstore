<?php

$path = $_REQUEST['path'];
if ($path == "" || $path == null || $path == "/index.php") {
  $path = "/";
}

//echo $path;

require 'vendor/autoload.php';

require_once 'controller/StoreController.php';
require_once 'controller/ContactController.php';
require_once 'controller/InscriptionController.php';
require_once 'controller/DebugController.php';
require_once 'controller/ErrorController.php';
require_once 'controller/UserController.php';
require_once 'controller/PagesController.php';

use Pux\Executor;

$mux = new Pux\Mux;
$mux->mount("/store", new StoreController());
$mux->mount("/contact", new ContactController());
$mux->mount("/inscription", new InscriptionController());
$mux->mount("/debug", new DebugController());
$mux->mount("/error", new ErrorController());
$mux->mount("/user", new UserController());
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
