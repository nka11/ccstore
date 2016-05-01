<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
class PagesController extends AbstractController {
  function index() {
    return $this->twig->render('index.html', array('name' => 'Fabien'));
  }
}
