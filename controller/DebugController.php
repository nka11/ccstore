<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
class DebugController extends AbstractController {

  function indexAction() {
    return parent::render('debug.html', array(
      "request_uri" => $_SERVER['REQUEST_URI'],
      "controller" => json_encode($this, JSON_PRETTY_PRINT)));
  }
}
