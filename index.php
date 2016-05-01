<?php

$path = $_REQUEST['path'];
if ($path == "" || $path == null || $path == "index.php") {
  $path = "/";
}

require 'vendor/autoload.php';

require_once 'controller/PagesController.php';

use Pux\Executor;

$mux = new Pux\Mux;

$mux->get('/', ['PagesController','index']);


$route = $mux->dispatch( $path );
echo Executor::execute($route);
