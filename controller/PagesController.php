<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
class PagesController extends AbstractController {

  /**
   * @Route("/")
   * @Method("GET")
   */
  public function indexAction() {
    return parent::render('index.html');
  }

  /**
   * @Route("/about")
   * @Method("GET")
   */
  public function aboutAction() {
    return parent::render('about.html');

  }
}
