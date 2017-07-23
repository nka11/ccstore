<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
class PortalController extends AbstractController {

  /**
   * @Route("/")
   * @Method("GET")
   */
  public function indexAction() {
    return parent::render('portal.html');
  }
}