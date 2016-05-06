<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
class ContactController extends AbstractController {
  /**
   * @Route("/")
   * @Method("GET")
   */
  function indexAction() {
    return parent::render('contact.html');
  }
}