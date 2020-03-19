<?php

$path = $_REQUEST['path'];
if ($path == "" || $path == null || $path == "/index.php") {
  $path = "/";
}

require 'vendor/autoload.php';
require 'conf/controllers.cnf.default.php';

use Pux\Executor;

$mode= "";

if($mode == "maintenance"){
	$pass = explode("/", $path);
	if(!isset($_COOKIE["maintenance"])){
		//echo "Pas de cookie operateur<br/>";
		if($pass[1] == "maintenance"){
			//echo "...creation du cookie operateur...<br/>";
			setCookie( "maintenance", "operator", time()+60*60, '/', 'www.courtcircuit.bio', true, true);
			$path= "/";
		}else {
			//echo "<br/>redirection vers page de maintenance";exit();
			require "templates/construction.html";
			exit();
		}
	}else{
		//echo "Cookie opérateur détecté<br/>";
		if($pass[1] == "maintenance"){
			//echo "<br/>Demande de suppression cookie";
			setCookie( "maintenance", "operator", time()- 60*60, '/', 'www.courtcircuit.bio', true, true);
			//echo "<br/>redirection page maintenance";exit();
			require "templates/construction.html";
			exit();
		}
	}
	//echo "renouvellement du path";
	
	//echo "<br/>".$path;
}
	$mount = explode("/", $path);
	$controller= ($mount[1] != "")
						? ucfirst($mount[1])."Controller"
						: "PortalController";
	//echo "path : $path -- mount : $mount[1] -- controller : $controller";exit();
	//echo "Controller : ".$controller;
	//echo "<br/> Tentative redirection...";
	//exit();
	$mux = new Pux\Mux;
	$mux->mount("/".$mount[1], new $controller());

	// Define @route
	$route = ( $path == "/" || $path == "/".$mount[1])
			? $mux->dispatch( "$path/" )
			: $mux->dispatch( "$path" );
	//Execute $route
	if(!empty($route)) echo Executor::execute($route);
	else{
		$mux->mount("/error", new ErrorController());
		$route = $mux->dispatch( '/error/404/'."Action controller introuvable. Route = ".$route );
		echo Executor::execute($route);
	}

/*
try {
  $route = $mux->dispatch( "$path/" );
  echo "PATH : ".$path;
  echo "<br/>ROUTE : ".$route;exit();
  echo Executor::execute($route);
} catch (Error $e) {
  try {
    $route = $mux->dispatch( "$path/" );
    echo Executor::execute($route);
  } catch (Error $ee) {
	 $mux->mount("/error", new ErrorController());
    $route = $mux->dispatch( '/error/404/'.$ee );
    echo Executor::execute($route);
  }
}*/