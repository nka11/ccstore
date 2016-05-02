<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
class PagesController extends AbstractController {
  function index() {
    return parent::render('index.html');
  }
}
